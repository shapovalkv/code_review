<?php

namespace App;

use App\Enums\UserPermissionEnum;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Modules\Booking\Models\Booking;
use Modules\Candidate\Models\Candidate;
use Modules\MarketplaceUser\Models\MarketplaceUser;
use Modules\Company\Models\Company;
use Modules\Gig\Models\Gig;
use Modules\Gig\Models\GigOrder;
use Modules\Job\Models\JobCandidate;
use Modules\Marketplace\Models\Marketplace;
use Modules\Order\Models\Order;
use Modules\Review\Models\Review;
use Modules\User\Emails\EmailUserVerificationCodeRegister;
use Modules\User\Emails\ResetPasswordToken;
use Modules\User\Helpers\PermissionHelper;
use Modules\User\Models\Plan;
use Modules\User\Models\PlanFeature;
use Modules\User\Models\PromocodeUsage;
use Modules\User\Models\UserPlan;
use Modules\User\Models\UserWishList;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\User\Traits\HasRoles;
use Musonza\Chat\Models\Participation;
use Musonza\Chat\Traits\Messageable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;
use Modules\Payout\Traits\HasPayout;
use Modules\User\Emails\UserPermanentlyDelete;
use Modules\Company\Models\CompanyRequest;
use Modules\Job\Models\Job;

/**
 * @property Company company
 * @property int id
 * @property UserPlan currentUserPlan
 * @property UserPlan futureUserPlan
 * @property string verification_code
 * @property string email
 * @property User[]|Collection staff
 * @property User|null parent
 */
class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    public const RELATION_USER_PLAN = 'currentUserPlan';
    public const RELATION_FUTURE_USER_PLAN = 'futureUserPlan';
    public const RELATION_ORDERS = 'orders';
    public const RELATION_CANDIDATE = 'candidate';
    public const RELATION_COMPANY = 'company';

    public const RELATION_ROLE = 'role';
    public const RELATION_STAFF = 'staff';
    public const RELATION_PARENT = 'parent';

    use SoftDeletes;
    use Notifiable;
    use HasRoles;
    use HasApiTokens;
    use HasPayout;
    use Messageable;

    protected string $slugField = 'user_name';
    protected string $slugFromField = 'name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'address',
        'address2',
        'phone',
        'birthday',
        'city',
        'state',
        'country',
        'zip_code',
        'last_login_at',
        'avatar_id',
        'bio',
        'business_name',
        'need_update_pw',
        'show_tutorial_popup',
        'verification_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'publish'
    ];

    public function getMeta($key, $default = '')
    {
        $val = DB::table('user_meta')->where([
            'user_id' => $this->id,
            'name'    => $key
        ])->first();

        if (!empty($val)) {
            return $val->val;
        }

        return $default;
    }

    public function addMeta($key, $val, $multiple = false)
    {
        if (is_array($val) or is_object($val)) $val = json_encode($val);
        if ($multiple) {
            return DB::table('user_meta')->insert([
                'name'        => $key,
                'val'         => $val,
                'user_id'     => $this->id,
                'create_user' => Auth::id(),
                'created_at'  => date('Y-m-d H:i:s')
            ]);
        } else {
            $old = DB::table('user_meta')->where([
                'user_id' => $this->id,
                'name'    => $key
            ])->first();

            if ($old) {
                return DB::table('user_meta')->where('id', $old->id)->update([
                    'val'         => $val,
                    'update_user' => Auth::id(),
                    'updated_at'  => date('Y-m-d H:i:s')
                ]);
            } else {
                return DB::table('user_meta')->insert([
                    'name'        => $key,
                    'val'         => $val,
                    'user_id'     => $this->id,
                    'create_user' => Auth::id(),
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }
        }

    }

    public function updateMeta($key, $val)
    {

        return DB::table('user_meta')->where('user_id', $this->id)
            ->where('name', $key)
            ->update([
                'val'         => $val,
                'update_user' => Auth::id(),
                'updated_at'  => date('Y-m-d H:i:s')
            ]);
    }

    public function batchInsertMeta($metaArrs = [])
    {
        if (!empty($metaArrs)) {
            foreach ($metaArrs as $key => $val) {
                $this->addMeta($key, $val, true);
            }
        }
    }

    public function getNameOrEmailAttribute()
    {
        if ($this->first_name) return $this->first_name;

        return $this->email;
    }


    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case "publish":
                return __("Publish");
                break;
            case "blocked":
                return __("Blocked");
                break;
        }
    }

    public static function getUserBySocialId($provider, $socialId)
    {
        return parent::query()->select('users.*')->join('user_meta as m', 'm.user_id', 'users.id')
            ->where('m.name', 'social_' . $provider . '_id')
            ->where('m.val', $socialId)->first();
    }

    public function getAvatarUrl()
    {
        $me = $this->parent ? $this->parent : $this;

        if (!empty($me->avatar_id)) {
            return get_file_url($me->avatar_id, 'thumb');
        }
        if (!empty($meta_avatar = $me->getMeta("social_meta_avatar", false))) {
            return $meta_avatar;
        }
        return asset('images/avatar.png');
    }

    public function getUserAvatar($default_type = 'image')
    {
        $display_name = $this->getDisplayName();
        if (!empty($this->avatar_id)) {
            return '<img src="' . get_file_url($this->avatar_id, 'thumb') . '" alt="' . $display_name . '">';
        }
        if (!empty($meta_avatar = $this->getMeta("social_meta_avatar", false))) {
            return '<img src="' . $meta_avatar . '" alt="' . $display_name . '">';
        }
        if ($default_type == 'text') {
            return '<span class="user-text">' . trim($display_name)[0] . '</span>';
        }
        return '<img src="' . asset('images/avatar.png') . '" alt="' . $display_name . '">';
    }

    public function getAvatarUrlAttribute()
    {
        return $this->getAvatarUrl();
    }

    public function getDisplayName($email = false)
    {
        $name = $this->name ?? "";
        if (!empty($this->first_name) or !empty($this->last_name)) {
            $name = implode(' ', [$this->first_name, $this->last_name]);
        }
        if (!empty($this->business_name)) {
            $name = $this->business_name;
        }
        if (!trim($name) and $this->email) $name = $this->email;
        if (empty(trim($name))) {
            $name = '';
        }
        return $name;
    }

    public function getShortCutName($email = false)
    {
        $name = $this->name ?? "";
        if (!empty($this->first_name) or !empty($this->last_name)) {
            $name = ucfirst($this->first_name) . ' ' . ucfirst(strtoupper(mb_substr($this->last_name, 0, 1))) . '.';
        }
        if (!empty($this->business_name)) {
            $name = $this->business_name;
        }
        if (!trim($name) and $this->email) $name = $this->email;
        if (empty(trim($name))) {
            $name = '';
        }
        return $name;
    }

    public function getDisplayNameAttribute()
    {
        $name = $this->name;
        if (!empty($this->first_name) or !empty($this->last_name)) {
            $name = implode(' ', [$this->first_name, $this->last_name]);
        }
        if (!empty($this->business_name)) {
            $name = $this->business_name;
        }
        return $name;
    }

    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)->send(new ResetPasswordToken($token, $this));
    }

    public static function boot()
    {
        parent::boot();
        static::saving(function ($table) {
            $table->name = implode(' ', [$table->first_name, $table->last_name]);
        });
        static::deleted(function ($table) {
            Job::where('create_user', $table->id)->delete();
            Company::where('create_user', $table->id)->delete();
            GigOrder::where('create_user', $table->id)->delete();
            Candidate::where('create_user', $table->id)->delete();
        });
    }

    public function getVendorServicesQuery($moduleClass, $limit = 10)
    {
        return $moduleClass::getVendorServicesQuery()->take($limit);
    }

    public function getReviewCountAttribute()
    {
        return Review::query()->where('vendor_id', $this->id)->where('status', 'approved')->count('id');
    }

    public function companyRequest()
    {
        return $this->hasOne(CompanyRequest::class);
    }

    public function getPayoutAccountsAttribute()
    {
        return json_decode($this->getMeta('vendor_payout_accounts'));
    }

    /**
     * Get total available amount for payout at current time
     */
    public function getAvailablePayoutAmountAttribute()
    {
        $status = setting_item_array('vendor_payout_booking_status');
        if (empty($status)) return 0;

        $query = Booking::query();

        $total = $query
                ->whereIn('status', $status)
                ->where('vendor_id', $this->id)
                ->sum(DB::raw('total_before_fees - commission + vendor_service_fee_amount')) - $this->total_paid;
        return max(0, $total);
    }

    public function getTotalPaidAttribute()
    {
        return VendorPayout::query()->where('status', '!=', 'rejected')->where([
            'vendor_id' => $this->id
        ])->sum('amount');
    }

    public function getAvailablePayoutMethodsAttribute()
    {
        $vendor_payout_methods = json_decode(setting_item('vendor_payout_methods'));
        if (!is_array($vendor_payout_methods)) $vendor_payout_methods = [];

        $vendor_payout_methods = array_values(\Illuminate\Support\Arr::sort($vendor_payout_methods, function ($value) {
            return $value->order ?? 0;
        }));

        $res = [];

        $accounts = $this->payout_accounts;

        if (!empty($vendor_payout_methods) and !empty($accounts)) {
            foreach ($vendor_payout_methods as $vendor_payout_method) {
                $id = $vendor_payout_method->id;

                if (!empty($accounts->$id)) {
                    $vendor_payout_method->user = $accounts->$id;
                    $res[$id] = $vendor_payout_method;
                }
            }
        }

        return $res;
    }


    /**
     * @return array
     * @todo get All Fields That you need to verification
     */
    public function getVerificationFieldsAttribute()
    {

        $all = get_all_verify_fields();
        $role_id = $this->role_id;
        $res = [];
        foreach ($all as $id => $field) {
            if (!empty($field['roles']) and is_array($field['roles']) and in_array($role_id, $field['roles'])) {
                $field['id'] = $id;
                $field['field_id'] = 'verify_data_' . $id;
                $field['is_verified'] = $this->isVerifiedField($id);
                $field['data'] = old('verify_data_' . $id, $this->getVerifyData($id));

                switch ($field['type']) {
                    case "multi_files":
                        $field['data'] = json_decode($field['data'], true);
                        if (!empty($field['data'])) {
                            foreach ($field['data'] as $k => $v) {
                                if (!is_array($v)) {
                                    $field['data'][$k] = json_decode($v, true);
                                }
                            }
                        }
                        break;
                }
                $res[$id] = $field;
            }
        }

        return \Illuminate\Support\Arr::sort($res, function ($value) {
            return $value['order'] ?? 0;
        });

    }

    public function isVerifiedField($field_id)
    {
        return (bool)$this->getMeta('is_verified_' . $field_id);
    }

    public function getVerifyData($field_id)
    {
        return $this->getMeta('verify_data_' . $field_id);
    }

    public static function countVerifyRequest()
    {
        return parent::query()->whereIn('verify_submit_status', ['new', 'partial'])->count(['id']);
    }

    public static function countUpgradeRequest()
    {
        return parent::query()->whereIn('verify_submit_status', ['new', 'partial'])->count(['id']);
    }

    public function sendEmailVerificationNotification(): void
    {
        Mail::to($this->email)->send(new EmailUserVerificationCodeRegister($this, $this->generateVerificationCode()));
    }

    public function generateVerificationCode(): string
    {
        $code = random_int(100000, 999999);
        $this->setAttribute('verification_code', $code);
        $this->save();

        return $code;
    }

    public function verificationUrl()
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 180)),
            ['id'   => $this->id,
             'hash' => sha1($this->getEmailForVerification()),
            ]
        );
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function creditPaymentUpdate($payment)
    {

        if ($payment->status == 'completed') {
            $this->deposit($payment->getMeta('credit'), $payment->getMeta());
        }
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'owner_id', 'id');
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'id', 'id');
    }

    public function marketplace_user()
    {
        return $this->hasOne(MarketplaceUser::class, 'id', 'id');
    }

    public function fillByAttr($attributes, $input)
    {
        if (!empty($attributes)) {
            foreach ($attributes as $item) {
                $this->$item = isset($input[$item]) ? ($input[$item]) : null;
            }
        }
    }

    public function getWishlistCountAttribute()
    {
        if ($this->hasPermission('candidate_manage')) {
            return UserWishList::query()->where('user_id', $this->id)->where('object_model', 'job')->count('id');
        } else {
            return UserWishList::query()->where('user_id', $this->id)->where('object_model', 'candidate')->count('id');
        }
    }

    public function getDetailUrl()
    {
        return route('user.profile', ['id' => $this->user_name ? $this->user_name : $this->id]);
    }

    /**
     * @Deprecated
     */
    public function user_plan()
    {
        return $this->hasOne(UserPlan::class, 'create_user')->where('status', '=', 1);
    }

    public function currentUserPlan(): HasOne
    {
        if ($this->parent) {
            return $this->parent->currentUserPlan();
        }

        return $this->hasOne(UserPlan::class, 'create_user')
            ->whereHas(UserPlan::RELATION_PLAN, static fn(Builder $builder) => $builder->where('plan_type', Plan::TYPE_RECURRING))
            ->where('status', '=', 1);
    }

    public function futureUserPlan(): HasOne
    {
        return $this->hasOne(UserPlan::class, 'create_user')->where('status', '=', 0)->whereDate('start_date', '>', Carbon::now());
    }

    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class, 'create_user');
    }

    public function isEmployerApplied($UserObjectId, $employerId = false)
    {
        if (!empty($employerId)) {
            $employer_company = User::find($employerId)->company;
        }
        $companyId = $employer_company->id ?? $this->company->id;
        return JobCandidate::query()->where('candidate_id', '=', $UserObjectId)
            ->when(!empty($companyId), function ($query) use ($companyId) {
                $query->where('company_id', '=', $companyId);
            })
            ->where('status', '=', Job::APPROVED)
            ->exists();
    }

    public function useFeature($slug, $counter = -1): bool
    {
        $userPlan = $this->currentUserPlan;
        if (!$userPlan) {
            return false;
        }
        if (!$userPlan->is_valid) {
            return false;
        }
        $features = $userPlan->features;
        if (!isset($features[$slug])) {
            return false;
        }
        if ($features[$slug] === '') {
            return true;
        }
        $features[$slug] = $features[$slug] + $counter;
        $userPlan->features = $features;
        return $userPlan->save();
    }

    public function checkChatPlan(): bool
    {
        return $this->checkFeature(PlanFeature::CHAT_ACCESS, 0, true);
    }

    public function checkSubAccountPlan($updated = false): bool
    {
        return $this->checkFeature(PlanFeature::SUB_ACCOUNTS, $this->staff()->withoutTrashed()->count(), $updated);
    }

    public function checkJobPlan($updated = false): bool
    {
        if (!setting_item('job_require_plan')) return true;
        return $this->checkFeature(PlanFeature::JOB_CREATE,
            $this->company->jobs()
                ->whereDate('expiration_date', '>=', date('Y-m-d'))
                ->where('status', Job::PUBLISH)
                ->count(),
            $updated);
    }

    public function checkFeaturedJobPlan($updated = false, $readable = false): bool
    {
        if (!setting_item('job_require_plan')) return true;
        return $this->checkFeature(PlanFeature::JOB_SPONSORED,
            $this->company->jobs()
                ->where('is_featured', 1)
                ->whereDate('expiration_date', '>=', date('Y-m-d'))
                ->where('status', Job::PUBLISH)
                ->count(),
            $updated,
            $readable);
    }

    public function checkAnnouncementPlan(int $count, $updated = false): bool
    {
        return $this->checkFeature(PlanFeature::ANNOUNCEMENT_CREATE, $count, $updated);
    }

    protected function checkFeature(string $slug, int $jobCount, bool $updated = false, bool $readable = false): bool
    {
        if (is_admin() && $readable) return true;

        $userPlan = $this->currentUserPlan;

        if (!$userPlan || !$userPlan->is_valid) {
            return false;
        }

        $feature = (int)$userPlan->plan->features->where('slug', $slug)->first()?->value;

        /** @var UserPlan $plan */
        foreach($this->userPlans()->where('status', UserPlan::NOT_USED)->get() as $plan) {
            if (!empty($plan->features[$slug])) {
                $feature += (int)$plan->features[$slug];
            }
        }

        if ($feature === 0) {
            return false;
        }

        if ($feature === -1) {
            return true;
        }

        if ($readable) {
            return $readable;
        }

        if ($updated) {
            return $feature >= $jobCount;
        }

        return $feature > $jobCount;
    }

    public function getCurrentPlanFeatureCount($slug): int
    {
        $userPlan = $this->currentUserPlan;

        if (!$userPlan || !$userPlan->is_valid) {
            return 0;
        }

        return (int)$userPlan->plan->features->where('slug', $slug)->first()?->value;

//        /** @var UserPlan $plan */
//        foreach($this->userPlans()->where('status', UserPlan::NOT_USED)->get() as $plan) {
//            if (!empty($plan->features[$slug])) {
//                $feature += (int)$plan->features[$slug];
//            }
//        }
//
//        return $feature;
    }

    public function marketplacesPublish(): HasMany
    {
        return $this->hasMany(Marketplace::class, 'author_id', 'id')->where('status', Marketplace::STATUS_PUBLISH);
    }

    public function checkCompanyInfo()
    {
        if (empty($this->company)) return false;
        if (empty($this->company->name)) return false;
        if (empty($this->company->phone)) return false;
        if (empty($this->company->email)) return false;

        return true;
    }

    public function checkGigPostingMaximum()
    {
        if (!setting_item('gig_require_plan')) return true;

        $max_allowed = $this->getMaximumPublishItems();

        $count_service = $this->gigsPublish()->count('id');

        if ($count_service >= $max_allowed) {
            return false;
        }
        return true;
    }

    public function gigs()
    {
        return $this->hasMany(Gig::class, 'author_id', 'id');
    }

    public function gigsPublish()
    {
        return $this->hasMany(Gig::class, 'author_id', 'id')->where('status', 'publish');
    }

    public function applyPlan(Plan $plan, $price, $isAnnual = false, ?Carbon $startDate = null): void
    {
        $userPlan = new UserPlan();

        $maxService = $plan->max_service;

        if (!$startDate) {
            $startDate = Carbon::now();
        }

        $endDate = clone $startDate;

        if ($isAnnual) {
            $endDate->addYear();
        } else {
            $endDate->modify('+ ' . $plan->duration . ' ' . $plan->duration_type);
        }
        $planData = $plan->toArray();
        $features = [];
        foreach ($plan->features as $feature) {
            $features[$feature->slug] = $feature->value;
        }

        $planData['is_annual'] = $isAnnual;

        $status = 0;

        if ($plan->plan_type === Plan::TYPE_RECURRING && $startDate->isPast()) {
            $status = UserPlan::CURRENT;
        } elseif($plan->plan_type === Plan::TYPE_RECURRING && $startDate->isFuture()) {
            $status = UserPlan::WAITING;
        }

        $data = [
            'plan_id'     => $plan->id,
            'price'       => $price,
            'start_date'  => $startDate->format('Y-m-d H:i:s'),
            'end_date'    => $endDate->format('Y-m-d H:i:s'),
            'max_service' => $maxService,
            'plan_data'   => $planData,
            'features'    => $features,
            'create_user' => $this->id,
            'status'      => $status
        ];
        $userPlan->fillByAttr(array_keys($data), $data);
        $userPlan->save();
    }

    public function applyPlanByAdmin(Plan $plan, $month_count = null)
    {
        $currentUserPlan = $this->currentUserPlan;
        if ($currentUserPlan) {
            $currentUserPlan->status = 0;
            $currentUserPlan->save();
        }

        if (!$plan->price || $plan->price == 0) {
            $new_user_plan = UserPlan::query()->where('create_user', '=', $this->id)->whereNull('price')->first();
        }

        if (empty($new_user_plan)) {
            $new_user_plan = new UserPlan();
        }

        if ($month_count) {
            $end_date = strtotime('+ ' . $month_count . ' month');
        } else {
            $end_date = strtotime('+ ' . $plan->duration . ' ' . $plan->duration_type);
        }

        $plan_data = $plan->toArray();
        $features = [];
        foreach ($plan->features as $feature) {
            $features[$feature->slug] = $feature->value;
        }
        $plan_data['is_annual'] = false;
        $data = [
            'plan_id'     => $plan->id,
            'price'       => 0, // to exclude from reports
            'start_date'  => date('Y-m-d H:i:s'),
            'end_date'    => date('Y-m-d H:i:s', $end_date),
            'max_service' => $plan->max_service,
            'plan_data'   => $plan_data,
            'features'    => $features,
            'create_user' => $this->id,
            'status'      => 1
        ];
        $new_user_plan->fillByAttr(array_keys($data), $data);
        $new_user_plan->save();

        return $new_user_plan;
    }

    public function applyPackageByAdmin(Plan $plan): UserPlan
    {
        $userPlan = new UserPlan();

        $plan_data = $plan->toArray();
        $features = [];
        foreach ($plan->features as $feature) {
            $features[$feature->slug] = $feature->value;
        }
        $plan_data['is_annual'] = false;
        $data = [
            'plan_id'     => $plan->id,
            'price'       => 0,
            'start_date'  => date('Y-m-d H:i:s'),
            'end_date'    => date('Y-m-d H:i:s'),
            'max_service' => $plan->max_service,
            'plan_data'   => $plan_data,
            'features'    => $features,
            'create_user' => $this->id,
            'status'      => UserPlan::NOT_USED,
        ];
        $userPlan->fillByAttr(array_keys($data), $data);
        $userPlan->save();

        return $userPlan;
    }

    public function cancelPlanByAdmin()
    {
        $currentUserPlan = $this->currentUserPlan;
        if ($currentUserPlan) {
            $currentUserPlan->status = 0;
            return $currentUserPlan->save();

        } else {
            return false;
        }
    }

    public function sendEmailPermanentlyDelete()
    {
        if (!empty(setting_item('user_enable_permanently_delete_email'))) {
//                to admin
            if (!empty(setting_item_with_lang('user_permanently_delete_content_email_to_admin'))) {
                Mail::to(setting_item('admin_email'))->send(new UserPermanentlyDelete($this, 1));
            }
            if (!empty(setting_item_with_lang('user_permanently_delete_content_email'))) {
                Mail::to($this->email)->send(new UserPermanentlyDelete($this, 0));
            }
        }

    }

    public function getMaximumPublishItems()
    {
        return $this->userPlans()->where('end_date', '>', date('Y-m-d H:i:s'))->sum('max_service');
    }

    public function isUserProfileFilled()
    {
        if (is_candidate() && empty(Auth::user()->candidate->title)) return false;
        if (is_candidate() && empty(Auth::user()->candidate->location_id)) return false;
        return true;
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'verification_code' => null,
            'is_verified'       => true,
        ])->save();
    }

    public function chatParticipation()
    {
        return $this->hasMany(Participation::class, 'messageable_id', 'id');
    }

    public function isUserOnline()
    {
        if (empty($this->last_activity_at)) {
            return false;
        }
        return Carbon::now()->subMinutes(10)->timestamp <= Carbon::createFromFormat('Y-m-d H:i:s', $this->last_activity_at)->timestamp;
    }

    public function getParticipantDetails($currentUser = null)
    {
        $name = $currentUser && $currentUser->id === $this->id ? 'Me' : $this->getNameAttribute();

        return [
            'name' => $name
        ];
    }


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id')->orderBy('created_at', 'desc');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id')->withTrashed()->orderBy('created_at', 'desc');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(UserPermission::class, 'user_id');
    }

    public function hasPermission(string|array $permissions): bool
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($this->hasPermission($permission)) {
                    return true;
                }
            }

            return false;
        }

        if($this->role && $this->role->hasPermission($permissions)) return true;

        if (in_array($permissions, UserPermissionEnum::getConstants(), true)) {
            return null !== $this->permissions()->get()->whereIn('permission', [$permissions, UserPermissionEnum::COMPANY_FULL_ACCESS])->first();
        }

        return false;
    }
}

