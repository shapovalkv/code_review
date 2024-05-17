<?php
namespace Modules\Job\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Modules\Company\Models\Company;
use Modules\Location\Models\Location;
use Modules\Media\Helpers\FileHelper;
use Modules\Skill\Models\Skill;

/**
 * @property Carbon expiration_date
 * @property COmpany company
 */
class Job extends BaseModel
{
    use SoftDeletes;

    public const PUBLISH = 'publish';
    public const DRAFT = 'draft';

    public const CACHE_KEY_DRAFT = 'draft.job';

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
        'experience_type',
        'status',
        'create_user',
        'apply_type',
        'apply_link',
        'apply_email',
        'wage_agreement',
        'number_recruitments',
        'gallery',
        'video',
        'is_approved',
        'is_featured',
        'is_urgent',
        'position_id',
        'key_responsibilities',
        'skills_and_exp',
        'employment_location'
    ];
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type = 'job';
    public $type = 'job';

    protected $casts = [
        'expiration_date' => 'datetime',
//        'employment_location' => 'array',
    ];

    protected $dates = [
        'expiration_date'
    ];

    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const DELETE = 'delete';
    const EMPTY_PLAN_JOB_EXPIRATION = 5;

    public static function getAll()
    {
        return self::with('cat')->get();
    }

    public function getEditUrl()
    {
        $lang = $this->lang ?? setting_item("site_locale");
        return route('job.admin.edit',['id'=>$this->id , "lang"=> $lang]);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'create_user', 'id');
    }

    public function location(){
        return $this->belongsTo(Location::class,'location_id','id');
    }

    public function category(){
        return $this->belongsTo(JobCategory::class,'category_id','id');
    }

    public function position(){
        return $this->belongsTo(JobPosition::class,'position_id','id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id')->withTrashed();
    }

    public function jobApplicants()
    {
        return $this->hasMany(JobCandidate::class, 'job_id');
    }

    public function getCandidateAppliedJob()
    {
        return $this->jobApplicants()->where('candidate_id', auth()->user()->id)->first();
    }

    public function getCandidateJobAppliedStatus()
    {
        if (!empty($candidateAppliedJob = $this->getCandidateAppliedJob())){
            if ($candidateAppliedJob->create_user == auth()->user()->id){
                switch ($candidateAppliedJob->status){
                    case JobCandidate::PENDING_STATUS:
                        return ucfirst(JobCandidate::APPLIED_STATUS);
                    case JobCandidate::APPROVED_STATUS:
                        return ucfirst(JobCandidate::APPROVED_STATUS);
                    case JobCandidate::REJECTED_STATUS:
                        return ucfirst(JobCandidate::REJECTED_STATUS);
                    default:
                        return 'Other statuses not provided. Please notify admin if you see this message.';
                }
            }else{
                switch ($candidateAppliedJob->status){
                    case JobCandidate::PENDING_STATUS:
                        return 'You were Invited';
                    case JobCandidate::APPROVED_STATUS:
                        return "Invite ".ucfirst(JobCandidate::APPROVED_STATUS);
                    case JobCandidate::REJECTED_STATUS:
                        return "Invite ".ucfirst(JobCandidate::REJECTED_STATUS);
                    default:
                        return 'Other statuses not provided. Please notify admin if you see this message.';
                }
            }
        }

        return 'ERROR';
    }

    public function getDetailUrl()
    {
        return url(app_get_locale(false, false, '/') . config('job.job_route_prefix') . "/" . $this->slug);
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

    public function isOpen(){
        if(empty($this->expiration_date)) return false;
        $estimate_time = strtotime($this->expiration_date) - strtotime('now');
        return $estimate_time >= 0;
    }

    public function jobType(){
        return $this->belongsTo(JobType::class,'job_type_id','id');
    }

    public function getSalary($show_type = true){
        $price_html = format_money($this->salary_min);
        if(!empty($this->salary_max) && $this->salary_max > $this->salary_min){
            $price_html .= ' - ' . format_money($this->salary_max);
        }
        if(!empty($this->salary_type) && $show_type){
            $price_html .= ' '.$this->getSalaryTypeNameAttribute();
        }
//        if(!empty($this->wage_agreement)){
//            $price_html = __("Wage Agreement");
//        }
        return $price_html;
    }

    public function getThumbnailUrl(){
        if(!empty($this->thumbnail_id)){
            return FileHelper::url($this->thumbnail_id);
        }elseif(!empty($this->company) && $this->company->avatar_id){
            return FileHelper::url($this->company->avatar_id);
        }elseif(!empty($this->user)){
            return $this->user->getAvatarUrl();
        }else{
            return false;
        }
    }

    public static function search(Request $request, $defaultSearchCount = null)
    {
        $model_job = parent::query()->select("bc_jobs.*");
        $model_job->where("bc_jobs.status", self::PUBLISH);

        if(setting_item("job_need_approve")) {
            $model_job->Where('bc_jobs.is_approved', '=', 'approved');
        }

        if(!empty($agent_id  = $request->query('agent_id'))){
            $model_job->where('bc_jobs.create_user',$agent_id);
        }
        $location_id = $request->query('location') ?? $request->location;
        if (!empty($location_id)) {
            $location = Location::query()->where('id', $location_id)->where("status","publish")->first();
            if (!empty($location) && !empty($radius = $request->radius) && $radius != 0){
                $model_job->join('bc_locations', function ($join) use ($radius, $location_id, $location) {
                    $join->on('bc_locations.id', '=', 'bc_jobs.location_id')
                        ->whereRaw("(3959 * acos(
                            cos(radians($location->map_lat))
                            * cos(radians(bc_jobs.map_lat))
                            * cos(radians(bc_jobs.map_lng) - radians($location->map_lng))
                            + sin(radians($location->map_lat))
                            * sin(radians(bc_jobs.map_lat)))) < $radius"
                        );
                    });
            }elseif(!empty($location)){
                $model_job->join('bc_locations', function ($join) use ($location_id, $location) {
                    $join->on('bc_locations.id', '=', 'bc_jobs.location_id')
                    ->where('bc_jobs.location_id', $location_id);
                });
            }
        }

        if (!empty($zipcode = $request->query('zipcode'))) {
            $model_job->join('bc_locations', function ($join) use ($zipcode){
                $join->on('bc_locations.id', '=', 'bc_jobs.location_id')
                    ->where('bc_locations.zipcode', $zipcode);
            }) ;
        }

        $categories = array_filter((array)($request->query('categories') ?? $request->categories));
        if (!empty($categories)) {
                $model_job->join('bc_job_categories', function ($join) use ($categories) {
                    $join->on('bc_job_categories.id', '=', 'bc_jobs.category_id')
                        ->whereIn('bc_job_categories.id', $categories)
                    ;
                });
        }

        $model_job->join('bc_job_positions', function ($join) use ($request) {
            $join->on('bc_job_positions.id', '=', 'bc_jobs.position_id');
            if (!empty($employment_type_ids = $request->query('employment_type') ?? $request->employment_type)) {
                $join->whereIn('bc_job_positions.id', $employment_type_ids);
            }

            if ($request->routeIs('job.search.practicum')) {
                $join->where('bc_job_positions.slug', '=', 'practicum-site');
            } else {
                $join->where('bc_job_positions.slug', '!=', 'practicum-site');
            }
        });

        if (!empty($job_types = $request->query('job_type'))) {
            $model_job->whereIn('job_type_id', array_map('intval', $job_types));
        }

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
                $model_job->where('bc_jobs.created_at', '>=', $date_p);
            }
        }

        if (!empty($experiences = $request->query('experience'))) {
            $model_job->where(function ($query) use ($experiences){
                 if (!empty($experiences) && is_array($experiences)){
                     foreach ($experiences as $key => $exp){
                         if($exp == 'fresh') {
                             $exp = 0;
                         }
                         $exp = (int)$exp;
                         if ($key == 0) {
                             $query->where([
                                 ['experience', '>=' , $exp],
                                 ['experience', '<' , $exp + 1]
                             ]);
                         } else {
                             $query->orWhere([
                                 ['experience', '>=' , $exp],
                                 ['experience', '<' , $exp + 1]
                             ]);
                         }
                     }
                 }
            });
        }
        if (!empty($pri_from = $request->query('amount_from')) && !empty($pri_to = $request->query('amount_to'))) {
            $model_job->where(function ($jobs) use ($pri_from, $pri_to) {
                $jobs->where('bc_jobs.salary_min', '>=', $pri_from)
                    ->where('bc_jobs.salary_max', '<=', $pri_to);
            });
        }

        $terms = $request->query('terms');
        if($term_id = $request->query('term_id'))
        {
            $terms[] = $term_id;
        }

        if (is_array($terms) && !empty($terms)) {
            $model_job->join('bc_property_term as tt', 'tt.target_id', "bc_properties.id")->whereIn('tt.term_id', $terms);
        }


        if (!empty($keywords = $request->query("s"))) {
            if( setting_item('site_enable_multi_lang') && setting_item('site_locale') != app()->getLocale() ){
                $model_job->leftJoin('bc_job_translations', function ($join) {
                    $join->on('bc_jobs.id', '=', 'bc_job_translations.origin_id');
                });
                static::searchByKeywords('where', $model_job, 'bc_job_translations', 'title', $keywords);
            }else{
                static::searchByKeywords('where', $model_job, 'bc_jobs', 'title', $keywords);
            }
        }

        if(setting_item('job_hide_expired_jobs') == 1){
            $model_job->whereDate('expiration_date', '>=',  date('Y-m-d'));
        }

        $orderBy = $request->query("orderby");
        $orderBy = $orderBy ? $orderBy : 'default';

        switch ($orderBy) {
            case"new":
                $model_job->orderBy("id", "desc");
                break;
            case"old":
                $model_job->orderBy("id", "asc");
                break;
            case"name_high":
                $model_job->orderBy("title", "asc");
                break;
            case"name_low":
                $model_job->orderBy("title", "desc");
                break;
            case"default":
                $model_job
                    ->leftJoin('bc_companies', function ($join) { $join->on('bc_companies.id', '=', 'bc_jobs.company_id'); })
                    ->leftJoin('users', function ($join) { $join->on('bc_companies.create_user', '=', 'users.id'); })
                    ->leftJoin('user_plan', function ($join) {
                        $join->on('user_plan.create_user', '=', 'users.id')
                            ->where('user_plan.status', 1);
                    })
                    ->leftJoin('bc_plans', function ($join) { $join->on('bc_plans.id', '=', 'user_plan.plan_id'); })
                    ->orderByRaw('ifnull( `bc_jobs`.`is_featured`, 0) DESC , `bc_plans`.`sorting_value` DESC , `bc_jobs`.`created_at` DESC');
                break;
        }



        $model_job->groupBy("bc_jobs.id");

        $limit = $request->query('limit', $defaultSearchCount);
        return $model_job->with(['location','translations', 'category', 'company', 'jobType', 'wishlist'])->paginate($limit);
    }

    public static function getMinMaxPrice()
    {
        $model = parent::selectRaw('MIN( salary_min ) AS min_price ,
                                    MAX( salary_max ) AS max_price ')->where("status", "publish")->first();

        if (empty($model->min_price) and empty($model->max_price)) {
            return [
                1,
                100
            ];
        }
        return [
            $model->min_price,
            $model->max_price
        ];
    }

    static public function getSeoMetaForPageList()
    {
        $meta['seo_title'] = __("Find Jobs");
        if (!empty($title = setting_item_with_lang("job_page_list_seo_title"))) {
            $meta['seo_title'] = $title;
        }else if(!empty($title = setting_item_with_lang("job_page_search_title"))) {
            $meta['seo_title'] = $title;
        }
        if (Route::currentRouteName() === 'job.search.practicum') {
            $meta['seo_title'] = __('Practicum Sites');
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

    public function skills(){
        return $this->belongsToMany(Skill::class, 'bc_job_skills', 'job_id', 'skill_id');
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

    public function getSalaryTypeNameAttribute()
    {
        $salary_types = [
            'hourly' => __("hourly"),
            'daily' => __("daily"),
            'weekly' => __("weekly"),
            'monthly' => __("monthly"),
            'yearly' => __("yearly/annualy")
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

    public static function countVerifyRequest() {
        return self::where("is_approved", "waiting")->count();
    }
}
