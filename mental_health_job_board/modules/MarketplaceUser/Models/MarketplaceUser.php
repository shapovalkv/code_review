<?php
namespace Modules\MarketplaceUser\Models;

use App\BaseModel;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Job\Models\Job;
use Modules\Location\Models\Location;
use Modules\Media\Helpers\FileHelper;
use Modules\Skill\Models\Skill;


class MarketplaceUser extends BaseModel
{
    use SoftDeletes;

    public const RELATION_USER = 'user';

    protected $table = 'bc_marketplace_users';
    protected $fillable = [
        'title',
        'content',
        'cat_id',
        'avatar_id',
        'full_name',
        'email',
        'address',
        'address2',
        'phone',
        'birthday',
        'city',
        'state',
        'country',
        'zip_code',
        'bio',
        'education',
        'experience',
        'award',
        'social_media',
        'gallery',
        'video',
        'expected_salary',
        'salary_type',
        'website',
        'allow_search',
        'video_cover_id',
        'never_saved_before'
    ];
    protected $slugField     = 'slug';
    protected $slugFromField = 'name';
    protected $seo_type = 'marketplace_user';
    public $type = 'marketplace_user';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'education' => 'array',
        'experience' => 'array',
        'award' => 'array',
        'social_media' => 'array',
        'expected_salary'=>'float'
    ];


    public function getDetailUrlAttribute()
    {
        return url('marketplace_user-' . $this->slug);
    }

    public function geCategorylink()
    {
        return route('marketplace_user.category.index',['slug'=>$this->slug]);
    }

    public function cat()
    {
        return $this->belongsTo('Modules\MarketplaceUser\Models\Category');
    }

    public function cvs()
    {
        return $this->hasMany(MarketplaceUserCvs::class,'origin_id');
    }

    public function getDefaultCV()
    {
        return MarketplaceUserCvs::query()->where('create_user', '=', $this->id)->where('is_default', '=', 1)->first();
    }

    public function location(){
        return $this->belongsTo(Location::class,'location_id','id');
    }

    public static function getAll()
    {
        return self::with('cat')->get();
    }

    public function getDetailUrl($locale = false)
    {
        return url(app_get_locale(false,false,'/'). config('marketplace_user.marketplace_user_route_prefix')."/".($this->id));
    }

    public function skills(){
        return $this->belongsToMany(Skill::class, 'bc_marketplace_user_skills', 'origin_id', 'skill_id');
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'bc_marketplace_user_categories', 'origin_id', 'cat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'id','id')->withTrashed();
    }

    public function check_maximum_apply_job(){
        $maximum = setting_item('marketplace_user_maximum_job_apply', '');
        if (!empty($maximum)){
            $marketplace_user_limit_apply_by = setting_item('marketplace_user_limit_apply_by', '');
            $job_marketplace_users = JobMarketplaceUser::query()->where('marketplace_user_id', $this->id);
            switch ($marketplace_user_limit_apply_by){
                case 'day':
                    $today = date('Y-m-d 00:00:00');
                    $job_marketplace_users = $job_marketplace_users->where('created_at', '>=', $today)->groupBy('job_id')->get()->count();
                    if((int)$maximum <= $job_marketplace_users ){
                        return ['mess' => 'Your turns to apply for job positions (to day) have run out'];
                    }
                    break;
                case 'month':
                    $this_month = date('Y-m-01 00:00:00');
                    $job_marketplace_users = $job_marketplace_users->where('created_at', '>=', $this_month)->groupBy('job_id')->get()->count();
                    if((int)$maximum <= $job_marketplace_users ){
                        return ['mess' => 'Your turns to apply for job positions (this month) have run out'];
                    }
                    break;
                default:
                    $job_marketplace_users = $job_marketplace_users->groupBy('job_id')->get()->count();
                    if((int)$maximum <= $job_marketplace_users ){
                        return ['mess' => 'Your turns to apply for job positions have run out'];
                    }
                    break;
            }


        }
        return false;
    }

    public function getCategory()
    {
        $categories = [];
        if(!empty($this->cat_id)){
            $catSearch = explode(',', $this->cat_id);
            $categories = Category::whereIn('id', $catSearch)->get();
        }
        return $categories;
    }

    public static function searchForMenu($q = false)
    {
        $query = static::select('id', 'title as name');
        if (strlen($q)) {

            $query->where('title', 'like', "%" . $q . "%");
        }
        $a = $query->limit(10)->get();
        return $a;
    }

    static public function getSeoMetaForPageList()
    {
        $meta['seo_title'] = setting_item_with_lang("marketplace_user_page_list_seo_title", false, setting_item_with_lang("marketplace_user_page_search_title",false, __("MarketplaceUsers")));
        $meta['seo_desc'] = setting_item_with_lang("marketplace_user_page_list_seo_desc");
        $meta['seo_image'] = setting_item("marketplace_user_page_list_seo_image", null);
        $meta['seo_share'] = setting_item_with_lang("marketplace_user_page_list_seo_share");
        $meta['full_url'] = url(config('marketplace_user.marketplace_user_route_prefix'));
        return $meta;
    }

    public function getEditUrl()
    {
        $lang = $this->lang ?? setting_item("site_locale");
        return route('user.admin.detail',['id'=>$this->id, "lang"=> $lang]);
    }

    public function dataForApi($forSingle = false){
        $translation = $this->translateOrOrigin(app()->getLocale());
        $data = [
            'id'=>$this->id,
            'slug'=>$this->slug,
            'title'=>$translation->title,
            'content'=>$translation->content,
            'avatar_id'=>$this->avatar_id,
            'image_url'=>get_file_url($this->avatar_id,'full'),
            'category'=>Category::selectRaw("id,name,slug")->find($this->cat_id) ?? null,
            'created_at'=>display_date($this->created_at),
            'author'=>[
                'display_name'=>$this->getAuthor->getDisplayName(),
                'avatar_url'=>$this->getAuthor->getAvatarUrl()
            ],
            'url'=>$this->getDetailUrl()
        ];
        return $data;
    }
    public function getGallery($featuredIncluded = false)
    {
        if (empty($this->gallery))
            return $this->gallery;
        $list_item = [];
        if ($featuredIncluded and $this->image_id) {
            $list_item[] = [
                'large' => FileHelper::url($this->image_id, 'full'),
                'thumb' => FileHelper::url($this->image_id, 'thumb')
            ];
        }
        $items = explode(",", $this->gallery);
        foreach ($items as $k => $item) {
            $large = FileHelper::url($item, 'full');
            $thumb = FileHelper::url($item, 'thumb');
            $list_item[] = [
                'large' => $large,
                'thumb' => $thumb
            ];
        }
        return $list_item;
    }

    public function getImageUrl($size = "medium", $img = '')
    {
        $s_image = (!empty($img)) ? $img : $this->image_id;
        $url = FileHelper::url($s_image, $size);
        return $url ? $url : '';
    }

    public static function getMinMaxPrice()
    {
        $model = parent::selectRaw('MIN( expected_salary ) AS min_price ,
                                    MAX( expected_salary ) AS max_price ')->where("allow_search", "publish")->first();
        if (empty($model->min_price) and empty($model->max_price)) {
            return [
                0,
                100
            ];
        }
        return [
            $model->min_price,
            $model->max_price
        ];
    }

    public static function search(Request $request, $defaultSearchCount = null)
    {
        $model_marketplace_user = parent::query()->select("bc_marketplace_users.*");

        if (!empty($date_posted = $request->query('date_posted'))) {
            switch($date_posted){
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
            }
            if(!empty($date_p)) {
                $model_marketplace_user->where('bc_marketplace_users.created_at', '>=', $date_p);
            }
        }

        if(!empty( $marketplace_user_name = $request->query("s") )){
            $model_marketplace_user->leftJoin('users', function ($join) {
                $join->on('bc_marketplace_users.id', '=', 'users.id');
            });
            if( setting_item('site_enable_multi_lang') && setting_item('site_locale') != app()->getLocale() ){
                $model_marketplace_user->leftJoin('bc_marketplace_user_translations', function ($join) {
                    $join->on('bc_marketplace_users.id', '=', 'bc_marketplace_user_translations.origin_id');
                });
                $model_marketplace_user->where(function($model_marketplace_user) use ($marketplace_user_name){
                    $model_marketplace_user->where('bc_marketplace_user_translations.title', 'LIKE', '%' . $marketplace_user_name . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $marketplace_user_name . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $marketplace_user_name . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $marketplace_user_name . '%')
                        ->orWhere('bc_marketplace_users.title', 'LIKE', '%' . $marketplace_user_name . '%');
                });

            }else{
                $model_marketplace_user->where(function($model_marketplace_user) use ($marketplace_user_name) {
                    $model_marketplace_user->where('bc_marketplace_users.title', 'LIKE', '%' . $marketplace_user_name . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $marketplace_user_name . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $marketplace_user_name . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $marketplace_user_name . '%');
                });
            }


        }

        if(!empty($orderby = $request->query("orderby"))) {
            switch($orderby) {
                case"new":
                    $model_marketplace_user->orderBy("id", "desc");
                    break;
                case"old":
                    $model_marketplace_user->orderBy("id");
                    break;
                case"name_high":
                    $model_marketplace_user->orderBy("title");
                    break;
                case"name_low":
                    $model_marketplace_user->orderBy("title", "desc");
                    break;
                default:
                    $model_marketplace_user->orderBy("id", "desc");
                    break;
            }
        }else{
            $model_marketplace_user->orderBy("id", "desc");
        }

        $model_marketplace_user->groupBy("bc_marketplace_users.id");

        $limit = $request->query('limit', $defaultSearchCount);

        return $model_marketplace_user->with(['user'])->paginate($limit);
    }

    public function timeAgo() {
        if(empty($this->created_at)) return false;
        $estimate_time = strtotime('now') - strtotime($this->created_at);

        if( $estimate_time < 1 )
        {
            return false;
        }
        if(($estimate_time/86400) >= 1){
            return display_date($this->created_at);
        }
        $condition = array(
            60 * 60                 =>  __('hour(s) ago'),
            60                      =>  __('minute(s) ago'),
            1                       =>  __('second(s) ago'),
        );
        foreach( $condition as $secs => $str ){
            $d = $estimate_time / $secs;

            if( $d >= 1 ){
                if($d < 60 && $secs == 1){
                    return __("just now");
                }
                $r = round( $d );
                return $r . ' ' . $str;
            }
        }
        return display_date($this->created_at);
    }

    public function getGenderTextAttribute()
    {
        $genders = [
            'male' => __("Male"),
            'female' => __("Female")
        ];
        return $this->gender ? $genders[$this->gender] : __("male");
    }

    public function enableDownloadCV()
    {
        if(Auth::check()) {
            $jobMarketplaceUser = JobMarketplaceUser::query()
                ->where('marketplace_user_id', $this->id)
                ->whereHas('company', function ($q){
                    $q->where('owner_id', Auth::id());
                })
                ->first();

            return $jobMarketplaceUser ? true : false;
        }
        return false;
    }

    public function jobs() {
        return $this->belongsToMany(Job::class, "bc_job_marketplace_users", "marketplace_user_id");
    }

    public function appliedJobs()
    {
        return $this->hasMany(JobMarketplaceUser::class, "marketplace_user_id", "id");
    }

    public function getNameAttribute(){
        return $this->user->name ?? '';
    }
}
