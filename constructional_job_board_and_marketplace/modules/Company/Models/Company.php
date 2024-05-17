<?php

namespace Modules\Company\Models;

use App\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Core\Models\SEO;
use Modules\Company\Models\CompanyCategory as Category;
use Modules\Core\Models\Terms;
use Modules\Equipment\Models\Equipment;
use Modules\Job\Models\Job;
use Modules\Location\Models\Location;
use Modules\Core\Models\Attributes;
use Modules\Company\Models\CompanyTerm;
use Modules\Media\Helpers\FileHelper;
use Modules\Skill\Models\Skill;
use Modules\User\Models\User;

class Company extends BaseModel
{
    use SoftDeletes;

    protected $table = 'bc_companies';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'avatar_id',
        'cover_id',
        'founded_in',
        'allow_search',
        'owner_id',
        'category_id',
        'team_size',
        'about',
        'social_media',
        'city',
        'state',
        'country',
        'zip_code',
        'address',
        'slug',
        'status',
        'location_id',
        'map_lat',
        'map_lng',
        'is_featured',
        'video_url',
        'gallery',
        'video_cover_id',
        'is_completed'
    ];
    protected $slugField = 'slug';
    protected $slugFromField = 'name';
    protected $seo_type = 'companies';
    public $type = 'company';
    protected $avatar_url = 'avatar_url';
    protected $casts = [
        'social_media' => 'array',
        'map_lat' => 'float',
        'map_lng' => 'float'
    ];

    public function getDetailUrlAttribute()
    {
        return url('companies-' . $this->slug);
    }

    public function getDetailUrl($locale = false)
    {
        return url(app_get_locale(false, false, '/') . config('companies.companies_route_prefix') . "/" . $this->slug);
    }

    public function getAvatarUrl()
    {
        if (!empty($this->avatar_id)) {
            return get_file_url($this->avatar_id, 'thumb');
        }
        if (!empty($meta_avatar = $this->getMeta("social_meta_avatar", false))) {
            return $meta_avatar;
        }
        return asset('images/avatar.png');
    }

    public function companyTerm()
    {
        return $this->hasMany(CompanyTerm::class, "company_id");
    }

    public function category()
    {
        $catename = $this->hasOne(CompanyCategory::class, "id", "category_id");
        return $catename;
    }

    public function teamSize()
    {
        return $this->hasOne(Terms::class, 'id', 'team_size')->with(['translations']);
    }

    public function job()
    {
        return $this->hasMany(Job::class, 'company_id', 'id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id', 'id');
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'company_id', 'id');
    }

    public function getAuthor()
    {
        return $this->belongsTo(User::class, "owner_id", "id");
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'bc_company_skills', 'company_id', 'skill_id');
    }

    public function getImageUrl($size = "medium", $img = '')
    {
        $s_image = (!empty($img)) ? $img : $this->avatar_id;
        $url = FileHelper::url($s_image, $size);
        return $url ? $url : '';
    }

    public function getGallery($featuredIncluded = false)
    {
        $listItem = [];
        if ($featuredIncluded && $this->image_id) {
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

    public static function getMinMaxFoundedDate()
    {
        $model = parent::selectRaw('MIN( founded_in ) AS min_founded_date ,
                                    MAX( founded_in ) AS max_founded_date ')->where("status", "publish")->first();
        if (empty($model->min_price) and empty($model->max_price)) {
            return [
                'from' => '2000-01-01',
                'to' => Carbon::now(),
            ];
        }
        return [
            'from' => $model->min_founded_date,
            'to' => $model->max_founded_date
        ];
    }

    public static function search(Request $request)
    {
        $modelCompanies = parent::query()->select("bc_companies.*")
            ->where("bc_companies.status", "publish")
            ->where('allow_search', 1);
        if (!empty($keywords = $request->query("keywords"))) {
            static::searchByKeywords('where', $modelCompanies, 'bc_companies', 'name', $keywords);
            static::searchByKeywords('orWhere', $modelCompanies, 'bc_companies', 'about', $keywords);
        }

        $categorySlug = $request->query('category') ?? $request->category;
        if (!empty($categorySlug)) {
            $modelCompanies->join('bc_categories', 'bc_companies.category_id', '=', 'bc_categories.id')
                ->whereIn('bc_categories.slug', $categorySlug);
        }

        if (($locationName = $request->get('location')) && ($locationType = $request->get('location_type'))) {
            static::searchByLocation($modelCompanies, 'bc_companies', $locationName, $locationType, $request->get('location_state'));
        }

        if (!empty($zipcode = $request->query('zipcode'))) {
            $modelCompanies->join('bc_locations', function ($join) use ($zipcode) {
                $join->on('bc_locations.id', '=', 'bc_companies.location_id')
                    ->where('bc_locations.zipcode', $zipcode);
            });
        }

        if (!empty($fromDate = $request->query("from_date")) && !empty($to_date = $request->query("to_date"))) {
            $dayLastMonth = date("t", strtotime($to_date . "-12-01"));

            $modelCompanies->whereBetween('founded_in', [$fromDate . '-01-01', $to_date . '-12-' . $dayLastMonth]);
        }
        if (!empty($size = $request->query("team_size"))) {
            $modelCompanies->where('team_size', $size);
        }
        $terms = $request->query('terms');
        if (is_array($terms)) {
            $terms = array_filter($terms);
        }
        if (is_array($terms) && !empty($terms)) {
            $modelCompanies->join('bc_company_term as ct', 'ct.company_id', "bc_companies.id")->whereIn('ct.term_id', $terms);
        }
        $orderby = $request->query("orderby", 'newest');
        switch ($orderby) {
            case "random":
                $modelCompanies->inRandomOrder();
                break;
            case "oldest":
                $modelCompanies->orderBy('bc_companies.id', 'ASC');
                break;
            case "newest":
                $modelCompanies->orderBy('bc_companies.id', 'DESC');
                break;
        }
        $modelCompanies->withCount(['job' => function (Builder $query) {
            $query->where('status', 'publish');
        }]);

        if (!empty($orderby = $request->query("orderby"))) {
            switch ($orderby) {
                case"new":
                    $modelCompanies->orderBy("bc_companies.id", "desc");
                    break;
                case"old":
                    $modelCompanies->orderBy("bc_companies.id", "asc");
                    break;
                case"name_high":
                    $modelCompanies->orderBy("bc_companies.name", "asc");
                    break;
                case"name_low":
                    $modelCompanies->orderBy("bc_companies.name", "desc");
                    break;
                default:
                    $modelCompanies->orderBy("bc_companies.id", "desc");
                    break;
            }
        } else {
            $modelCompanies->orderBy("bc_companies.id", "desc");
        }

        if ($request->query("count")) {
            return $modelCompanies->count();
        }

        $limit = $request->query("limit", 10);
        return $modelCompanies->with(["category", "location", "companyTerm.term"])->groupBy("bc_companies.id")->paginate($limit);
    }

    public function getEditUrl()
    {
        $lang = $this->lang ?? setting_item("site_locale");
        return route('company.admin.edit', ['id' => $this->id, "lang" => $lang]);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar_url ? get_file_url($this->avatar_id) : null;
    }

    static public function getSeoMetaForPageList()
    {
        $meta['seo_title'] = setting_item_with_lang("company_page_list_seo_title", false, setting_item_with_lang("company_page_search_title", false, __("Companies")));
        $meta['seo_desc'] = setting_item_with_lang("company_page_list_seo_desc");
        $meta['seo_image'] = setting_item("company_page_list_seo_image", false);
        $meta['seo_share'] = setting_item_with_lang("company_page_list_seo_share");
        $meta['full_url'] = url(config('companies.companies_route_prefix'));
        return $meta;
    }

    public function dataForApi($user, $forSingle = false)
    {
        $translation = $this->translateOrOrigin(app()->getLocale());
        $items = [
            'id' => $this->id,
            'type' => $this->type,
            'slug' => $this->slug,
            'title' => $translation->title,
            'content' => $translation->content,
            'avatar_id' => $this->avatar_id,
            'avatar_url' => get_file_url($this->avatar_id, 'full'),
            'category' => Category::selectRaw("id,name,slug")->find($this->cat_id) ?? null,
            'created_at' => display_date($this->created_at),
            'author' => [
                'display_name' => $this->getAuthor->getDisplayName(),
                'avatar_url' => $this->getAuthor->getAvatarUrl()
            ],
            'local_url' => $this->getDetailUrl(),
            'external_url' => $this->website ?? null,
            'wish_list' => $user ? $user->hasWishList($this->type, $this->id)->exists() : false,
        ];
        return $items;
    }

}
