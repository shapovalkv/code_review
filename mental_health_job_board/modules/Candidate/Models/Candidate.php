<?php
namespace Modules\Candidate\Models;

use App\BaseModel;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Job\Models\JobCandidate;
use Modules\Job\Models\Job;
use Modules\Location\Models\Location;
use Modules\Media\Helpers\FileHelper;
use Modules\Skill\Models\Skill;


/**
 * @property array education
 * @property string languages
 */
class Candidate extends BaseModel
{
    use SoftDeletes;

    public const RELATION_USER = 'user';
    public const RELATION_CATEGORIES = 'categories';

    public const PUBLISH = 'publish';
    protected $table = 'bc_candidates';
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
        'never_saved_before',
        'map_lat',
        'map_lng',
        'map_zoom',
        'location_id'
    ];
    protected $slugField     = 'slug';
    protected $slugFromField = 'name';
    protected $seo_type = 'candidate';
    public $type = 'candidate';

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
        return url('candidate-' . $this->slug);
    }

    public function geCategorylink()
    {
        return route('candidate.category.index',['slug'=>$this->slug]);
    }

    public function cat()
    {
        return $this->belongsTo('Modules\Candidate\Models\Category');
    }

    public function cvs()
    {
        return $this->hasMany(CandidateCvs::class,'origin_id');
    }

    public function getDefaultCV()
    {
        return CandidateCvs::query()->where('create_user', '=', $this->id)->where('is_default', '=', 1)->first();
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
        return url(app_get_locale(false,false,'/'). config('candidate.candidate_route_prefix')."/".($this->id));
    }

    public function skills(){
        return $this->belongsToMany(Skill::class, 'bc_candidate_skills', 'origin_id', 'skill_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'bc_candidate_categories', 'origin_id', 'cat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'id','id')->withTrashed();
    }

    public function check_maximum_apply_job(){
        $maximum = setting_item('candidate_maximum_job_apply', '');
        if (!empty($maximum)){
            $candidate_limit_apply_by = setting_item('candidate_limit_apply_by', '');
            $job_candidates = JobCandidate::query()->where('candidate_id', $this->id);
            switch ($candidate_limit_apply_by){
                case 'day':
                    $today = date('Y-m-d 00:00:00');
                    $job_candidates = $job_candidates->where('created_at', '>=', $today)->groupBy('job_id')->get()->count();
                    if((int)$maximum <= $job_candidates ){
                        return ['mess' => 'Your turns to apply for job positions (to day) have run out'];
                    }
                    break;
                case 'month':
                    $this_month = date('Y-m-01 00:00:00');
                    $job_candidates = $job_candidates->where('created_at', '>=', $this_month)->groupBy('job_id')->get()->count();
                    if((int)$maximum <= $job_candidates ){
                        return ['mess' => 'Your turns to apply for job positions (this month) have run out'];
                    }
                    break;
                default:
                    $job_candidates = $job_candidates->groupBy('job_id')->get()->count();
                    if((int)$maximum <= $job_candidates ){
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
        $meta['seo_title'] = setting_item_with_lang("candidate_page_list_seo_title", false, setting_item_with_lang("candidate_page_search_title",false, __("Candidates")));
        $meta['seo_desc'] = setting_item_with_lang("candidate_page_list_seo_desc");
        $meta['seo_image'] = setting_item("candidate_page_list_seo_image", null);
        $meta['seo_share'] = setting_item_with_lang("candidate_page_list_seo_share");
        $meta['full_url'] = url(config('candidate.candidate_route_prefix'));
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
        $model_candidate = parent::query()->select("bc_candidates.*");

        if(setting_item('candidate_public_policy') == "employer_applied") {
            $user_id = Auth::id();
            $model_candidate->join("bc_job_candidates", "bc_candidates.id", "=" , "bc_job_candidates.candidate_id")
            ->join("bc_jobs", "bc_jobs.id", "=" , "bc_job_candidates.job_id")
            ->join("bc_companies", "bc_jobs.company_id", "=" , "bc_companies.id")
            ->where("bc_companies.owner_id", "=", $user_id);
        }

        $model_candidate->where("bc_candidates.allow_search", "publish");

        if (!empty($location_id = $request->query("location"))) {
            $location = Location::query()->where('id', $location_id)->where("status","publish")->first();
            if (!empty($location) && !empty($radius = $request->radius) && $radius != 0){
                $model_candidate->join('bc_locations', function ($join) use ($radius, $location_id, $location) {
                    $join->on('bc_locations.id', '=', 'bc_candidates.location_id')
                        ->whereRaw("(3959 * acos(
                            cos(radians($location->map_lat))
                            * cos(radians(bc_candidates.map_lat))
                            * cos(radians(bc_candidates.map_lng) - radians($location->map_lng))
                            + sin(radians($location->map_lat))
                            * sin(radians(bc_candidates.map_lat)))) < $radius"
                        );
                });
            }elseif(!empty($location)){
                $model_candidate->where('location_id', $location_id);
//                $model_candidate->join('bc_locations', function ($join) use ($location_id, $location) {
//                    $join->on('bc_locations.id', '=', 'bc_candidates.location_id')
//                        ->where('bc_locations._lft', '>=', $location->_lft)
//                        ->where('bc_locations._rgt', '<=', $location->_rgt);
//                });
            }
        }

        if (!empty($zipcode = $request->query('zipcode'))) {
            $model_candidate->join('bc_locations', function ($join) use ($zipcode){
                $join->on('bc_locations.id', '=', 'bc_candidates.location_id')
                    ->where('bc_locations.zipcode', $zipcode);
            }) ;
        }

        if (!empty($skill = $request->query('skill'))) {
            if(!empty($skill)){
                $model_candidate->join('bc_candidate_skills', function ($join) use ($skill) {
                    $join->on('bc_candidate_skills.origin_id', '=', 'bc_candidates.id')
                        ->where('bc_candidate_skills.skill_id', '=', $skill);
                });
            }
        }

        $categories = $request->query('categories') ?? $request->categories;
        if (!empty($categories)) {
            $model_candidate->join('bc_candidate_categories', function ($join) use ($categories) {
                $join->on('bc_candidate_categories.origin_id', '=', 'bc_candidates.id')
                    ->whereIn('bc_candidate_categories.cat_id', $categories)
                ;
            });
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
                $model_candidate->where('bc_candidates.created_at', '>=', $date_p);
            }
        }

        if (!empty($experiences = $request->query('experience_year'))) {
            $model_candidate->where(function ($query) use ($experiences){
                if (!empty($experiences)){
                    if(is_array($experiences)){
                        foreach ($experiences as $key => $exp){
                            if($exp == 'fresh') {
                                $exp = 0;
                            }
                            $exp = (int)$exp;
                            if ($key == 0) {
                                $query->where([
                                    ['experience_year', '>=' , $exp],
                                    ['experience_year', '<' , $exp + 1]
                                ]);
                            } else {
                                $query->orWhere([
                                    ['experience_year', '>=' , $exp],
                                    ['experience_year', '<' , $exp + 1]
                                ]);
                            }
                        }
                    }else{
                        $exp = (int)($experiences == 'fresh' ? 0 : $experiences);
                        $query->where([
                            ['experience_year', '>=' , $exp],
                            ['experience_year', '<' , $exp + 1]
                        ]);
                    }

                }
            });
        }

        if (!empty($education_level = $request->query('education_level'))) {
            $model_candidate->where(function ($query) use ($education_level){
                if (!empty($education_level)){
                    if(is_array($education_level)){
                        foreach ($education_level as $key => $level){
                            if ($key == 0) {
                                $query->where('education_level', 'LIKE', '%' . $level . '%' );
                            } else {
                                $query->orWhere('education_level', 'LIKE', '%' . $level . '%');
                            }
                        }
                    }else{
                        $query->where('education_level', 'LIKE', '%' . $education_level . '%');
                    }
                }
            });
        }


        if(!empty( $candidate_name = $request->query("s") )){
            $model_candidate->leftJoin('users', function ($join) {
                $join->on('bc_candidates.id', '=', 'users.id');
            });
            if( setting_item('site_enable_multi_lang') && setting_item('site_locale') != app()->getLocale() ){
                $model_candidate->leftJoin('bc_candidate_translations', function ($join) {
                    $join->on('bc_candidates.id', '=', 'bc_candidate_translations.origin_id');
                });
                $model_candidate->where(function($model_candidate) use ($candidate_name){
                    $model_candidate->where('bc_candidate_translations.title', 'LIKE', '%' . $candidate_name . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $candidate_name . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $candidate_name . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $candidate_name . '%')
                        ->orWhere('bc_candidates.title', 'LIKE', '%' . $candidate_name . '%');
                });

            }else{
                $model_candidate->where(function($model_candidate) use ($candidate_name) {
                    $model_candidate->where('bc_candidates.title', 'LIKE', '%' . $candidate_name . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $candidate_name . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $candidate_name . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $candidate_name . '%');
                });
            }


        }
        if (!empty($pri_from = $request->query('amount_from')) && !empty($pri_to = $request->query('amount_to'))) {

            if($pri_to >= $pri_from) {
                $model_candidate->whereBetween('expected_salary',[$pri_from,$pri_to]);
            }
        }
        if(!empty($orderby = $request->query("orderby"))) {
            switch($orderby) {
                case"new":
                    $model_candidate->orderBy("id", "desc");
                    break;
                case"old":
                    $model_candidate->orderBy("id");
                    break;
                case"name_high":
                    $model_candidate->orderBy("title");
                    break;
                case"name_low":
                    $model_candidate->orderBy("title", "desc");
                    break;
                default:
                    $model_candidate->orderBy("id", "desc");
                    break;
            }
        }else{
            $model_candidate->orderBy("id", "desc");
        }

        $model_candidate->groupBy("bc_candidates.id");

        $model_candidate->whereNotNull('bc_candidates.title');

        $limit = $request->query('limit', $defaultSearchCount);

        return $model_candidate->with(['translations', 'wishlist', 'user', 'categories'])->paginate($limit);
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
            $jobCandidate = JobCandidate::query()
                ->where('candidate_id', $this->id)
                ->whereHas('company', function ($q){
                    $q->where('owner_id', Auth::id());
                })
                ->first();

            return $jobCandidate ? true : false;
        }
        return false;
    }

    public function jobs() {
        return $this->belongsToMany(Job::class, "bc_job_candidates", "candidate_id");
    }

    public function appliedJobs()
    {
        return $this->hasMany(JobCandidate::class, "candidate_id", "id");
    }

    public function getNameAttribute(){
        return $this->user->name ?? '';
    }
}
