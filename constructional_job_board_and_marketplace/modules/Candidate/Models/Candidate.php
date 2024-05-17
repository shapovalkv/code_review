<?php

namespace Modules\Candidate\Models;

use App\BaseModel;
use App\User;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Job\Models\JobCandidate;
use Modules\Job\Models\JobType;
use Modules\Location\Models\Location;
use Modules\Media\Helpers\FileHelper;
use Modules\Skill\Models\Skill;
use Modules\User\Models\UserWishList;

class Candidate extends BaseModel
{
    use SoftDeletes;
    use InteractsWithViews;

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
        'seniority_level',
        'award',
        'social_media',
        'gallery',
        'video',
        'expected_salary_min',
        'expected_salary_max',
        'salary_type',
        'website',
        'allow_search',
        'video_cover_id',
        'job_type_id'
    ];
    protected $slugField = 'slug';
    protected $slugFromField = 'title';
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
        'expected_salary_min' => 'float',
        'map_lat' => 'float',
        'map_lng' => 'float'
    ];

    public function getDetailUrlAttribute()
    {
        return url('candidate-' . $this->slug);
    }

    public function geCategorylink()
    {
        return route('candidate.category.index', ['slug' => $this->slug]);
    }

    public function cat()
    {
        return $this->belongsTo('Modules\Candidate\Models\Category');
    }

    public function cvs()
    {
        return $this->hasMany(CandidateCvs::class, 'origin_id');
    }

    public function candidatesApplies()
    {
        return $this->hasMany(JobCandidate::class, 'candidate_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public static function getAll()
    {
        return self::with('cat')->get();
    }

    public function getDetailUrl($locale = false)
    {
        return url(app_get_locale(false, false, '/') . config('candidate.candidate_route_prefix') . "/" . $this->slug);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'bc_candidate_skills', 'origin_id', 'skill_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'bc_candidate_categories', 'origin_id', 'cat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class, 'job_type_id', 'id')
            ->where('status', 'publish');
    }

    public function check_maximum_apply_job()
    {
        $maximum = setting_item('candidate_maximum_job_apply', '');
        if (!empty($maximum)) {
            $candidate_limit_apply_by = setting_item('candidate_limit_apply_by', '');
            $job_candidates = JobCandidate::query()->where('candidate_id', $this->id);
            switch ($candidate_limit_apply_by) {
                case 'day':
                    $today = date('Y-m-d 00:00:00');
                    $job_candidates = $job_candidates->where('created_at', '>=', $today)->groupBy('job_id')->get()->count();
                    if ((int)$maximum <= $job_candidates) {
                        return ['mess' => 'Your turns to apply for job positions (to day) have run out'];
                    }
                    break;
                case 'month':
                    $this_month = date('Y-m-01 00:00:00');
                    $job_candidates = $job_candidates->where('created_at', '>=', $this_month)->groupBy('job_id')->get()->count();
                    if ((int)$maximum <= $job_candidates) {
                        return ['mess' => 'Your turns to apply for job positions (this month) have run out'];
                    }
                    break;
                default:
                    $job_candidates = $job_candidates->groupBy('job_id')->get()->count();
                    if ((int)$maximum <= $job_candidates) {
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
        if (!empty($this->cat_id)) {
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
        $meta['seo_title'] = setting_item_with_lang("candidate_page_list_seo_title", false, setting_item_with_lang("candidate_page_search_title", false, __("Candidates")));
        $meta['seo_desc'] = setting_item_with_lang("candidate_page_list_seo_desc");
        $meta['seo_image'] = setting_item("candidate_page_list_seo_image", null);
        $meta['seo_share'] = setting_item_with_lang("candidate_page_list_seo_share");
        $meta['full_url'] = url(config('candidate.candidate_route_prefix'));
        return $meta;
    }

    public function getEditUrl()
    {
        $lang = $this->lang ?? setting_item("site_locale");
        return route('user.admin.detail', ['id' => $this->id, "lang" => $lang]);
    }

    public function dataForApi($user, $forSingle = false)
    {
        $translation = $this->translateOrOrigin(app()->getLocale());
        return [
            'id' => $this->id,
            'type' => $this->type,
            'slug' => $this->slug,
            'title' => $translation->title,
            'languages' => $this->languages,
            'city' => $this->location ? $this->location->map_location : null,
            'category' => Category::selectRaw("id,name,slug")->find($this->cat_id) ?? null,
            'author' => [
                'display_name' => $this->getAuthor->getDisplayName(),
                'avatar_url' => $this->getAuthor->getAvatarUrl(),
                'old' => !empty($this->getAuthor->birthday) ? $this->getAuthor->getUserOld() : null,
            ],
            'url' => $this->getDetailUrl(),
            'wish_list' => $user ? $user->hasWishList($this->type, $this->id)->exists() : false,
        ];
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

    public function getImageUrl($size = "medium", $img = '')
    {
        $s_image = (!empty($img)) ? $img : $this->image_id;
        $url = FileHelper::url($s_image, $size);
        return $url ? $url : '';
    }

    public static function getMinMaxPrice()
    {
        $model = parent::selectRaw('MIN( expected_salary_min ) AS min_price ,
                                    MAX( expected_salary_min ) AS min_price_max,
                                    MAX( expected_salary_max ) AS max_price ')
            ->where("allow_search", "publish")->first();
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

    public static function search(Request $request)
    {
        $modelCandidate = parent::query()->select("bc_candidates.*");
        $modelCandidate->where("bc_candidates.allow_search", "publish");

        if (($locationName = $request->get('location')) && ($locationType =  $request->get('location_type'))) {
            static::searchByLocation($modelCandidate, 'bc_candidates', $locationName, $locationType, $request->get('location_state'));
        }

        if (!empty($zipcode = $request->query('zipcode'))) {
            $modelCandidate->join('bc_locations', function ($join) use ($zipcode) {
                $join->on('bc_locations.id', '=', 'bc_candidates.location_id')
                    ->where('bc_locations.zipcode', $zipcode);
            });
        }

        if (!empty($skill = $request->query('skill'))) {
            if (!empty($skill)) {
                $modelCandidate->join('bc_candidate_skills', function ($join) use ($skill) {
                    $join->on('bc_candidate_skills.origin_id', '=', 'bc_candidates.id')
                        ->where('bc_candidate_skills.skill_id', '=', $skill);
                });
            }
        }

        if (!empty($seniorityLevel = $request->query('seniority_level'))) {
            $modelCandidate->whereIn('seniority_level', $seniorityLevel);
        }

        if (!empty($gender = $request->query('gender'))) {
            if (!empty($gender)) {
                if (in_array('both', $gender)) {
                    $gender = array_diff($gender, ['both']);
                    $gender = array_unique(array_merge($gender, ['male', 'female']));
                }
                $modelCandidate->whereIn('gender', $gender);
            }
        }

        if (!empty($category = $request->query('category'))) {
            if (!empty($category)) {
                $modelCandidate->join('bc_candidate_categories', 'bc_candidate_categories.origin_id', '=', 'bc_candidates.id')
                    ->join('bc_categories', 'bc_candidate_categories.cat_id', '=', 'bc_categories.id')
                    ->whereIn('bc_categories.slug', $category);
            }
        }

        if (!empty($datePosted = $request->query('date_posted'))) {
            switch ($datePosted) {
                case 'last_hour':
                    $searchDatePosted = date('Y-m-d H:i:s', strtotime('-1 hour'));
                    break;
                case 'last_1':
                    $searchDatePosted = date('Y-m-d H:i:s', strtotime("-1 day"));
                    break;
                case 'last_7':
                    $searchDatePosted = date('Y-m-d H:i:s', strtotime("-1 week"));
                    break;
                case 'last_14':
                    $searchDatePosted = date('Y-m-d H:i:s', strtotime("-2 weeks"));
                    break;
                case 'last_30':
                    $searchDatePosted = date('Y-m-d H:i:s', strtotime("-1 month"));
                    break;
            }
            if (!empty($searchDatePosted)) {
                $modelCandidate->where('bc_candidates.created_at', '>=', $searchDatePosted);
            }
        }

        if (!empty($experiences = $request->query('experience_year'))) {
            $modelCandidate->where(function ($query) use ($experiences) {
                if (!empty($experiences)) {
                    if (is_array($experiences)) {
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
                                    ['experience_year', '>=', $exp],
                                    ['experience_year', '<', $exp + 1]
                                ]);
                            } else {
                                $query->orWhere([
                                    ['experience_year', '>=', $exp],
                                    ['experience_year', '<', $exp + 1]
                                ]);
                            }
                        }
                    } else {
                        $exp = (int)($experiences == 'fresh' ? 0 : $experiences);
                        $query->where([
                            ['experience_year', '>=', $exp],
                            ['experience_year', '<', $exp + 1]
                        ]);
                    }

                }
            });
        }

        if (!empty($educationLevel = $request->query('education_level'))) {
            $modelCandidate->where(function ($query) use ($educationLevel) {
                if (!empty($educationLevel)) {
                    if (is_array($educationLevel)) {
                        foreach ($educationLevel as $key => $level) {
                            if ($key == 0) {
                                $query->where('education_level', $level);
                            } else {
                                $query->orWhere('education_level', $level);
                            }
                        }
                    } else {
                        $query->where('education_level', $educationLevel);
                    }
                }
            });
        }


        if (!empty($keywords = $request->query("keywords"))) {
            $modelCandidate->leftJoin('users', function ($join) {
                $join->on('bc_candidates.id', '=', 'users.id');
            });

            static::searchByKeywords('where', $modelCandidate, 'users', 'name', $keywords);
            static::searchByKeywords('orWhere', $modelCandidate, 'bc_candidates', 'title', $keywords);
        }
        if (!empty($priFrom = $request->query('salary_from')) && !empty($pri_to = $request->query('salary_to'))) {
            if ($pri_to >= $priFrom) {
                $modelCandidate->whereBetween('bc_candidates.expected_salary_min', [$priFrom, $pri_to]);
            }
        }
        if (!empty($orderby = $request->query("orderby"))) {
            switch ($orderby) {
                case"new":
                    $modelCandidate->orderBy("bc_candidates.id", "desc");
                    break;
                case"old":
                    $modelCandidate->orderBy("bc_candidates.id", "asc");
                    break;
                case"name_high":
                    $modelCandidate->orderBy("bc_candidates.title", "asc");
                    break;
                case"name_low":
                    $modelCandidate->orderBy("bc_candidates.title", "desc");
                    break;
                default:
                    $modelCandidate->orderBy("bc_candidates.id", "desc");
                    break;
            }
        } else {
            $modelCandidate->orderBy("bc_candidates.id", "desc");
        }

        $modelCandidate->groupBy("bc_candidates.id");

        $modelCandidate->whereNotNull('title');

        if ($request->query("count")) {
            return $modelCandidate->count();
        }

        $limit = $request->query('limit', 10);
        return $modelCandidate->with(['translations', 'user', 'categories', 'location'])->paginate($limit);
    }

    public function timeAgo()
    {
        if (empty($this->created_at)) return false;
        $estimate_time = strtotime('now') - strtotime($this->created_at);

        if ($estimate_time < 1) {
            return false;
        }
        if (($estimate_time / 86400) >= 1) {
            return display_date($this->created_at);
        }
        $condition = array(
            60 * 60 => __('hour(s) ago'),
            60 => __('minute(s) ago'),
            1 => __('second(s) ago'),
        );
        foreach ($condition as $secs => $str) {
            $d = $estimate_time / $secs;

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
        if (Auth::check()) {
            $jobCandidate = JobCandidate::query()
                ->where('candidate_id', $this->id)
                ->whereHas('company', function ($q) {
                    $q->where('owner_id', Auth::id());
                })
                ->first();

            return $jobCandidate ? true : false;
        }
        return false;
    }
}
