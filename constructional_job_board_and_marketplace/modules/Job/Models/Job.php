<?php

namespace Modules\Job\Models;

use App\BaseModel;
use Carbon\Carbon;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Company\Models\Company;
use Modules\Location\Models\Location;
use Modules\Media\Helpers\FileHelper;
use Modules\Skill\Models\Skill;
use Modules\Job\Models\JobCategory as Category;
use Modules\User\Models\UserWishList;

class Job extends BaseModel implements Viewable
{
    use SoftDeletes;
    use InteractsWithViews;

    const STATUS_PUBLISH = 'publish';

    protected $table = 'bc_jobs';
    protected $fillable = [
        'title',
        'content',
        'category_id',
        'thumbnail_id',
        'location_id',
        'company_id',
        'job_type_id',
        'expiration_date',
        'hours',
        'hours_type',
        'salary_min',
        'salary_max',
        'salary_type',
        'gender',
        'map_lat',
        'map_lng',
        'map_zoom',
        'experience',
        'seniority_level',
        'status',
        'create_user',
        'apply_type',
        'apply_link',
        'apply_email',
        'wage_agreement',
        'number_recruitments',
        'gallery',
        'video',
        'is_featured'
    ];
    protected $slugField = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type = 'job';
    protected $casts = ['map_lat' => 'float', 'map_lng' => 'float'];
    public $type = 'job';

    const BASIC_EXPIRATION_DAYS = 20;

    public static function getAll()
    {
        return self::with('cat')->get();
    }

    public function getEditUrl()
    {
        $lang = $this->lang ?? setting_item("site_locale");
        return route('job.admin.edit', ['id' => $this->id, "lang" => $lang]);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'author_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'category_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function candidates()
    {
        return $this->hasMany(JobCandidate::class, 'job_id', 'id');
    }

    public function getDetailUrl()
    {
        return url(app_get_locale(false, false, '/') . config('job.job_route_prefix') . "/" . $this->slug);
    }

    public function timeAgo()
    {
        if (empty($this->created_at)) return false;
        $estimateTime = strtotime('now') - strtotime($this->created_at);

        if ($estimateTime < 1) {
            return false;
        }
        if (($estimateTime / 86400) >= 1) {
            return display_date($this->created_at);
        }
        $condition = array(
            60 * 60 => __('hour(s) ago'),
            60 => __('minute(s) ago'),
            1 => __('second(s) ago'),
        );

        foreach ($condition as $secs => $str) {
            $d = $estimateTime / $secs;

            if ($d >= 1) {
                if ($d < 60 && $secs == 1) {
                    return __("just now");
                }
                $r = round($d);
                return $r . ' ' . $str;
            }
        }
        return display_date($this->created_at);
    }

    public function isOpen()
    {
        if (empty($this->expiration_date)) return false;
        $estimateTime = strtotime($this->expiration_date) - strtotime('now');
        return $estimateTime >= 0;
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class, 'job_type_id', 'id')
            ->where('status', 'publish');
    }

    public function getSalary($showType = true)
    {
        $priceHtml = format_money($this->salary_min);
        if (!empty($this->salary_max)) {
            $priceHtml .= ' - ' . format_money($this->salary_max);
        }
        if (!empty($this->salary_type) && $showType) {
            $priceHtml .= ' /' . $this->salary_type_name;
        }
        if (!empty($this->wage_agreement)) {
            $priceHtml = __("Wage Agreement");
        }
        return $priceHtml;
    }

    public function getThumbnailUrl()
    {
        if (!empty($this->thumbnail_id)) {
            return FileHelper::url($this->thumbnail_id);
        } elseif (!empty($this->company) && $this->company->avatar_id) {
            return FileHelper::url($this->company->avatar_id);
        } elseif (!empty($this->user)) {
            return $this->user->getAvatarUrl();
        } else {
            return false;
        }
    }

    public static function search(Request $request, $companyId = null)
    {
        $modelJob = parent::query()->select("bc_jobs.*");

        if (!is_null($companyId)) {
            $modelJob->where('bc_jobs.company_id', $companyId);
        } else {
            $modelJob->where("bc_jobs.status", "publish");
        }

        if (!empty(!empty($searchByCompanyId = $request->query('searchByCompanyId') ?? $request->searchByCompanyId))){
            $modelJob->where('bc_jobs.company_id', $searchByCompanyId);
        }

        if (!empty($request->query('active'))) {
            $modelJob->where("bc_jobs.status", $request->query('active'));
        }

        if ($sponsored = $request->query('sponsored')) {
            $modelJob->whereIn("bc_jobs.is_featured", $sponsored);
        }

        if (!empty($agentId = $request->query('agent_id'))) {
            $modelJob->where('bc_jobs.create_user', $agentId);
        }

        if (($locationName = $request->get('location')) && ($locationType =  $request->get('location_type'))) {
            static::searchByLocation($modelJob, 'bc_jobs', $locationName, $locationType, $request->get('location_state'));
        }

        if (!empty($zipcode = $request->query('zipcode'))) {
            $modelJob->join('bc_locations', function ($join) use ($zipcode) {
                $join->on('bc_locations.id', '=', 'bc_jobs.location_id')
                    ->where('bc_locations.zipcode', $zipcode);
            });
        }

        $categorySlug = $request->query('category') ?? $request->category;
        if (!empty($categorySlug)) {
            $modelJob->join('bc_categories', 'bc_jobs.category_id', '=', 'bc_categories.id')
                ->whereIn('bc_categories.slug', $categorySlug);
        }

        $jobSkills = $request->query('skill');
        if (!empty($jobSkills)) {
            $modelJob->join('bc_job_skills', 'bc_jobs.id', 'bc_job_skills.job_id')
                ->join('bc_skills', 'bc_job_skills.skill_id', 'bc_skills.id')
                ->whereIn('bc_skills.slug', $jobSkills);
        }

        if (!empty($jobTypes = $request->query('job_type'))) {
            $modelJob->join('bc_job_types', 'bc_jobs.job_type_id', '=', 'bc_job_types.id')
                ->whereIn('bc_job_types.slug', $jobTypes);
        }

        if (!empty($datePosted = $request->query('date_posted'))) {
            switch ($datePosted) {
                case 'last_hour':
                    $date_p = date('Y-m-d H:i:s', strtotime('-1 hour'));
                    break;
                case 'last_1':
                    $date_p = date('Y-m-d H:i:s', strtotime("-1 day"));
                    break;
                case 'last_7':
                    $date_p = date('Y-m-d H:i:s', strtotime("-1 week"));
                    break;
                case 'last_14':
                    $date_p = date('Y-m-d H:i:s', strtotime("-2 weeks"));
                    break;
                case 'last_30':
                    $date_p = date('Y-m-d H:i:s', strtotime("-1 month"));
                    break;
                case 'range':
                    $dateRange = $request->query('date_posted');
                    $dateFrom = date('Y-m-d', $dateRange['dateFrom']);
                    $dateTo = date('Y-m-d', $dateRange['dateTo']);
            }
            if (!empty($date_p)) {
                $modelJob->where('bc_jobs.created_at', '>=', $date_p);
            }

            if (!empty($dateFrom) && !empty($dateTo)) {
                $modelJob->whereBetween('bc_jobs.created_at', [$dateFrom, $dateTo]);
            }
        }

        if (!empty($experiences = $request->query('experience'))) {
            $modelJob->where(function ($query) use ($experiences) {
                if (!empty($experiences) && is_array($experiences)) {
                    foreach ($experiences as $key => $exp) {
                        if ($exp == 'tempered') {
                            $query->orWhere([
                                ['experience', '>', 5],
                            ]);
                            continue;
                        }
                        if ($exp == 'fresh') {
                            $exp = 0;
                        }
                        $exp = (int)$exp;
                        if ($key == 0) {
                            $query->where([
                                ['experience', '>=', $exp],
                                ['experience', '<', $exp + 1]
                            ]);
                        } else {
                            $query->orWhere([
                                ['experience', '>=', $exp],
                                ['experience', '<', $exp + 1]
                            ]);
                        }
                    }
                }
            });
        }

        if (!empty($seniorityLevel = $request->query('seniority_level'))) {
            $modelJob->whereIn('bc_jobs.seniority_level', $seniorityLevel);
        }

        if ($priFrom = $request->query('salary_from')) {
            $modelJob->whereRaw('bc_jobs.salary_min >= ?', $priFrom);
        }
        if ($priTo = $request->query('salary_to')) {
            $modelJob->whereRaw('IFNULL(bc_jobs.salary_min, 0) <= ?', $priTo);
        }

        $terms = $request->query('terms');
        if ($term_id = $request->query('term_id')) {
            $terms[] = $term_id;
        }

        if (is_array($terms) && !empty($terms)) {
            $modelJob->join('bc_property_term as tt', 'tt.target_id', "bc_properties.id")->whereIn('tt.term_id', $terms);
        }


        if (!empty($keywords = $request->query("keywords"))) {
            static::searchByKeywords('where', $modelJob, 'bc_jobs', 'title', $keywords);
        }

        if (setting_item('job_hide_expired_jobs') == 1) {
            $modelJob->where('bc_jobs.expiration_date', '>=', date('Y-m-d H:s:i'));
        }

        if (!empty($orderby = $request->query("orderby"))) {
            switch ($orderby) {
                case"new":
                    $modelJob->orderBy("bc_jobs.id", "desc");
                    break;
                case"old":
                    $modelJob->orderBy("bc_jobs.id", "asc");
                    break;
                case"name_high":
                    $modelJob->orderBy("bc_jobs.title", "asc");
                    break;
                case"name_low":
                    $modelJob->orderBy("bc_jobs.title", "desc");
                    break;
                default:
                    $modelJob->orderBy("bc_jobs.is_featured", "desc");
                    $modelJob->orderBy("bc_jobs.id", "desc");
                    break;
            }
        } else {
            $modelJob->orderBy("bc_jobs.is_featured", "desc");
            $modelJob->orderBy("bc_jobs.id", "desc");
        }

        $modelJob->groupBy("bc_jobs.id");

        if ($request->query("count")) {
            return $modelJob->count();
        }

        $limit = $request->query('limit', 10);

        return $modelJob->with(['location', 'translations', 'category', 'company', 'jobType', 'skills', 'candidates'])->paginate($limit);
    }

    public static function getMinMaxPrice()
    {
        $model = self::selectRaw('MIN( salary_min ) AS min_price ,
                                    MAX( salary_min ) AS min_price_max,
                                    MAX( salary_max ) AS max_price
                                    ')->where("status", "publish")->first();
        if (empty($model->min_price) && empty($model->max_price)) {
            return [
                'salary_from' => 0,
                'salary_to' => 100,
            ];
        }
        return [
            'salary_from' => $model->min_price,
            'salary_to' => $model->max_price ?? $model->min_price_max
        ];
    }

    static public function getSeoMetaForPageList()
    {
        $meta['seo_title'] = __("Find Jobs");
        if (!empty($title = setting_item_with_lang("job_page_list_seo_title"))) {
            $meta['seo_title'] = $title;
        } else if (!empty($title = setting_item_with_lang("job_page_search_title"))) {
            $meta['seo_title'] = $title;
        }
        $meta['seo_image'] = null;
        if (!empty($title = setting_item("job_page_list_seo_image"))) {
            $meta['seo_image'] = $title;
        }
        $meta['seo_desc'] = setting_item_with_lang("job_page_list_seo_desc");
        $meta['seo_share'] = setting_item_with_lang("job_page_list_seo_share");
        $meta['full_url'] = url(config('job.job_route_prefix'));
        return $meta;
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'bc_job_skills', 'job_id', 'skill_id');
    }

    public function getGallery($featuredIncluded = false)
    {
        $listItem = [];
        if ($featuredIncluded and $this->image_id) {
            $listItem[] = [
                'large' => FileHelper::url($this->image_id, 'full'),
                'thumb' => FileHelper::url($this->image_id, 'thumb')
            ];
        }
        if (empty($this->gallery))
            return $listItem;
        $items = explode(",", $this->gallery);
        foreach ($items as $k => $item) {
            $large = FileHelper::url($item, 'full');
            $thumb = FileHelper::url($item, 'thumb');
            $listItem[] = [
                'large' => $large,
                'thumb' => $thumb
            ];
        }
        return $listItem;
    }

    public function getSalaryTypeNameAttribute()
    {
        $salary_types = [
            'hourly' => __("hourly"),
            'daily' => __("daily"),
            'weekly' => __("weekly"),
            'monthly' => __("monthly"),
            'yearly' => __("yearly")
        ];
        return $this->salary_type ? $salary_types[$this->salary_type] : '';
    }

    public function getGenderTextAttribute()
    {
        $genders = [
            'Both' => __("Both"),
            'Male' => __("Male"),
            'Female' => __("Female")
        ];
        return $this->gender ? $genders[$this->gender] : __("Both");
    }

    public function dataForApi($user, $forSingle = false)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'slug' => $this->slug,
            'title' => $this->title,
            'hours' => $this->hours,
            'experience' => $this->experience,
            'hours_type' => $this->hours_type,
            'salary_type' => $this->salary_type,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'is_featured' => $this->is_featured,
            'wish_list' => $user ? $user->hasWishList($this->type, $this->id)->exists() : false,
            'is_urgent' => $this->is_urgent,
            'url' => $this->getDetailUrl(),
            'location' => $this->location ? $this->location->only(['id', 'name', 'slug']) : null,
            'category' => $this->category ? $this->category->only(['id', 'name', 'slug', 'ancestors']) : null,
            'company' => $this->company ? $this->company->only(['name', 'city', 'avatar_url']) : null,
            'job_type' => $this->jobType ? $this->jobType->only(['id', 'name', 'slug']) : null,
            'job_skills' => $this->skills ? $this->skills->map(function ($skill) {
                return $skill->only(['id', 'name', 'slug']);
            }) : null,
        ];
    }

    public function publish($params = [])
    {
        return $this->update(array_merge([
            'status' => self::STATUS_PUBLISH,
            'expiration_date' => Carbon::now()->addDays(Job::BASIC_EXPIRATION_DAYS),
        ], $params));
    }

    public function sponsored()
    {
        return $this->publish([
            'is_featured' => true
        ]);
    }
}
