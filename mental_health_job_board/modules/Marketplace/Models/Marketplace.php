<?php

namespace Modules\Marketplace\Models;

use App\Currency;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Modules\Booking\Models\Bookable;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingTimeSlots;
use Modules\Company\Models\Company;
use Modules\Core\Models\Attributes;
use Modules\Location\Models\Location;
use Modules\Media\Helpers\FileHelper;
use Modules\News\Models\Tag;
use Modules\Order\Models\OrderItem;
use Modules\Review\Models\Review;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\UserWishList;

/**
 * @property \Illuminate\Support\Carbon expiration_date
 * @property \Illuminate\Support\Carbon announcement_date
 * @property int company_id
 * @property int author_id
 * @property string announcement_status
 * @property string status
 * @property string slug
 * @property ?int id
 */
class Marketplace extends Bookable
{
    public const RELATION_ORDER_ITEM = 'orderItem';
    public const CACHE_KEY_DRAFT = 'draft.marketplace';

    use Notifiable;
    use SoftDeletes;

    protected $table = 'bc_marketplaces';
    public $type = 'Marketplace';

    protected $fillable = [
        'title',
        'content',
        'announcement_status',
        'status',
        'company_id',
        'map_lat',
        'map_lng',
        'map_zoom',
        'location_id',
        'expiration_date',
        'announcement_date',
        'video_cover_image_id',
        'thumbnail_id',
        'website',
        'is_featured',
        'is_package',
        'author_id',
        'cat_id'
    ];
    protected $slugField = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type = 'Marketplace';
    protected $casts = [
        'packages' => 'array',
        'extra_price' => 'array',
        'package_compare' => 'array',
        'requirements' => 'array',
        'faqs' => 'array',
        'map_lat' => 'float',
        'map_lng' => 'float',
    ];
    protected $bookingClass;
    protected $bookingTimeSlotsClass;
    protected $reviewClass;
    protected $MarketplaceDateClass;
    protected $MarketplaceTranslationClass;
    protected $userWishListClass;
    protected $locationClass;

    const BASIC_EXPIRATION_DAYS = 31;

    public const STATUS_PUBLISH = 'publish';
    public const STATUS_DRAFT = 'draft';

    protected $attributes = [
        'status'          => 'draft',
        'expiration_date' => null,
    ];

    protected $dates = [
        'announcement_date',
        'expiration_date'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->bookingClass = Booking::class;
        $this->bookingTimeSlotsClass = BookingTimeSlots::class;
        $this->reviewClass = Review::class;
        $this->MarketplaceTranslationClass = MarketplaceTranslation::class;
        $this->userWishListClass = UserWishList::class;
        $this->locationClass = Location::class;
    }

    public function isActive(): bool
    {
        return $this->expiration_date && $this->expiration_date->timestamp >= \Illuminate\Support\Carbon::now()->endOfDay()->timestamp;
    }

    public static function getModelName()
    {
        return __("Marketplace");
    }

    public static function getTableName()
    {
        return with(new static)->table;
    }

    /**
     * Get SEO fop page list
     *
     * @return mixed
     */
    static public function getSeoMetaForPageList()
    {
        $meta['seo_title'] = __("Search for Marketplaces");
        if (!empty($title = setting_item_with_lang("Marketplace_page_list_seo_title", false))) {
            $meta['seo_title'] = $title;
        } else if (!empty($title = setting_item_with_lang("Marketplace_page_search_title"))) {
            $meta['seo_title'] = $title;
        }
        $meta['seo_image'] = null;
        if (!empty($title = setting_item("Marketplace_page_list_seo_image"))) {
            $meta['seo_image'] = $title;
        } else if (!empty($title = setting_item("Marketplace_page_search_banner"))) {
            $meta['seo_image'] = $title;
        }
        $meta['seo_desc'] = setting_item_with_lang("Marketplace_page_list_seo_desc");
        $meta['seo_share'] = setting_item_with_lang("Marketplace_page_list_seo_share");
        $meta['full_url'] = url(env('Marketplace_ROUTE_PREFIX', 'Marketplace'));
        return $meta;
    }


    public function getDetailUrl($include_param = true)
    {
        $param = [];
        if ($include_param) {
            if (!empty($date = request()->input('date'))) {
                $dates = explode(" - ", $date);
                if (!empty($dates)) {
                    $param['start'] = $dates[0] ?? "";
                    $param['end'] = $dates[1] ?? "";
                }
            }
            if (!empty($adults = request()->input('adults'))) {
                $param['adults'] = $adults;
            }
            if (!empty($children = request()->input('children'))) {
                $param['children'] = $children;
            }
        }
        $urlDetail = app_get_locale(false, false, '/') . env('Marketplace_ROUTE_PREFIX', 'marketplace') . "/" . $this->slug;
        if (!empty($param)) {
            $urlDetail .= "?" . http_build_query($param);
        }
        return url($urlDetail);
    }

    public static function getLinkForPageSearch($locale = false, $param = [])
    {

        return url(app_get_locale(false, false, '/') . env('Marketplace_ROUTE_PREFIX', 'Marketplace') . "?" . http_build_query($param));
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
        $items = array_filter(explode(",", $this->gallery));
        $thumbnails = json_decode($this->thumbnails, true);
        foreach ($items as $k => $item) {
            $large = FileHelper::url($item, 'full');
            $thumb = FileHelper::url(($thumbnails[$item] ?? $item), ($thumbnails[$item] ?? null) ? 'full' : 'thumb');
            $listItem[] = [
                'large' => $large,
                'thumb' => $thumb
            ];
        }
        return $listItem;
    }

    public function getEditUrl()
    {
        return url(route('Marketplace.admin.edit', ['id' => $this->id]));
    }

    public function getDiscountPercentAttribute()
    {
        if (!empty($this->price) and $this->price > 0
            and !empty($this->sale_price) and $this->sale_price > 0
            and $this->price > $this->sale_price
        ) {
            $percent = 100 - ceil($this->sale_price / ($this->price / 100));
            return $percent . "%";
        }
    }

//    public function fill(array $attributes)
//    {
//        if (!empty($attributes)) {
//            foreach ($this->fillable as $item) {
//                $attributes[$item] = $attributes[$item] ?? null;
//            }
//        }
//        return parent::fill($attributes); // TODO: Change the autogenerated stub
//    }

    public function isBookable()
    {
        if ($this->status != 'publish')
            return false;
        return parent::isBookable();
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

    public static function getMinMaxPrice()
    {
        $model = parent::selectRaw('MIN( price ) AS min_price ,
                                    MAX( price ) AS max_price ')->where("status", "publish")->first();
        if (empty($model->min_price) and empty($model->max_price)) {
            return [
                'price_from' => 0,
                'price_to' => 100,
            ];
        }
        return [
            'price_from' => $model->min_price,
            'price_to' => $model->max_price
        ];
    }

    public function getReviewEnable()
    {
        return setting_item("Marketplace_enable_review", 0);
    }

    public function getReviewApproved()
    {
        return setting_item("Marketplace_review_approved", 0);
    }

    public static function getReviewStats()
    {
        $reviewStats = [];
        if (!empty($list = setting_item("Marketplace_review_stats", []))) {
            $list = json_decode($list, true);
            foreach ($list as $item) {
                $reviewStats[] = $item['title'];
            }
        }
        return $reviewStats;
    }

    public function getReviewDataAttribute()
    {
        $list_score = [
            'score_total' => 0,
            'score_text' => __("Not rated"),
            'total_review' => 0,
            'rate_score' => [],
        ];
        $dataTotalReview = $this->reviewClass::selectRaw(" AVG(rate_number) as score_total , COUNT(id) as total_review ")->where('object_id', $this->id)->where('object_model', $this->type)->where("status", "approved")->first();
        if (!empty($dataTotalReview->score_total)) {
            $list_score['score_total'] = number_format($dataTotalReview->score_total, 1);
            $list_score['score_text'] = Review::getDisplayTextScoreByLever(round($list_score['score_total']));
        }
        if (!empty($dataTotalReview->total_review)) {
            $list_score['total_review'] = $dataTotalReview->total_review;
        }
        $list_data_rate = $this->reviewClass::selectRaw('COUNT( CASE WHEN rate_number = 5 THEN rate_number ELSE NULL END ) AS rate_5,
                                                            COUNT( CASE WHEN rate_number = 4 THEN rate_number ELSE NULL END ) AS rate_4,
                                                            COUNT( CASE WHEN rate_number = 3 THEN rate_number ELSE NULL END ) AS rate_3,
                                                            COUNT( CASE WHEN rate_number = 2 THEN rate_number ELSE NULL END ) AS rate_2,
                                                            COUNT( CASE WHEN rate_number = 1 THEN rate_number ELSE NULL END ) AS rate_1 ')->where('object_id', $this->id)->where('object_model', $this->type)->where("status", "approved")->first()->toArray();
        for ($rate = 5; $rate >= 1; $rate--) {
            if (!empty($number = $list_data_rate['rate_' . $rate])) {
                $percent = ($number / $list_score['total_review']) * 100;
            } else {
                $percent = 0;
            }
            $list_score['rate_score'][$rate] = [
                'title' => $this->reviewClass::getDisplayTextScoreByLever($rate),
                'total' => $number,
                'percent' => round($percent),
            ];
        }
        return $list_score;
    }

    public function getScoreReview()
    {
        $Marketplace_id = $this->id;
        $list_score = Cache::rememberForever('review_' . $this->type . '_' . $Marketplace_id, function () use ($Marketplace_id) {
            $dataReview = $this->reviewClass::selectRaw(" AVG(rate_number) as score_total , COUNT(id) as total_review ")->where('object_id', $Marketplace_id)->where('object_model', $this->type)->where("status", "approved")->first();
            $score_total = !empty($dataReview->score_total) ? number_format($dataReview->score_total, 1) : 0;
            return [
                'score_total' => $score_total,
                'total_review' => !empty($dataReview->total_review) ? $dataReview->total_review : 0,
            ];
        });
        $list_score['review_text'] = $list_score['score_total'] ? Review::getDisplayTextScoreByLever(round($list_score['score_total'])) : __("Not rated");
        return $list_score;
    }

    public function getNumberReviewsInService($status = false)
    {
        return $this->reviewClass::countReviewByServiceID($this->id, false, $status, $this->type) ?? 0;
    }

    public function getReviewList()
    {
        return $this->reviewClass::select(['id', 'title', 'content', 'rate_number', 'author_ip', 'status', 'created_at', 'vendor_id', 'create_user'])->where('object_id', $this->id)->where('object_model', 'Marketplace')->where("status", "approved")->orderBy("id", "desc")->with('author')->paginate(setting_item('Marketplace_review_number_per_page', 5));
    }


    public function getNumberServiceInLocation($location)
    {
        $number = 0;
        if (!empty($location)) {
            $number = parent::join('bc_locations', function ($join) use ($location) {
                $join->on('bc_locations.id', '=', $this->table . '.location_id')->where('bc_locations._lft', '>=', $location->_lft)->where('bc_locations._rgt', '<=', $location->_rgt);
            })->where($this->table . ".status", "publish")->with(['translations'])->count($this->table . ".id");
        }
        if (empty($number)) return false;
        if ($number > 1) {
            return __(":number Marketplaces", ['number' => $number]);
        }
        return __(":number Marketplace", ['number' => $number]);
    }

    public static function getServiceIconFeatured()
    {
        return "flaticon-climbing";
    }

    public static function isEnable()
    {
        return setting_item('Marketplace_disable') == false;
    }

    public function isDepositEnable()
    {
        return (setting_item('Marketplace_deposit_enable') and setting_item('Marketplace_deposit_amount'));
    }

    public function getDepositAmount()
    {
        return setting_item('Marketplace_deposit_amount');
    }

    public function getDepositType()
    {
        return setting_item('Marketplace_deposit_type');
    }

    public function getDepositFomular()
    {
        return setting_item('Marketplace_deposit_fomular', 'default');
    }


    public static function search(Request $request, $defaultListCountSearch, $authorId = null)
    {
        $modelMarketplace = parent::query()->select("bc_marketplaces.*");
        $modelMarketplace->whereDate('expiration_date', '>=', Carbon::now());

        if (!is_null($authorId)) {
            $modelMarketplace->where('bc_marketplaces.author_id', $authorId);
        } else {
            $modelMarketplace->where("bc_marketplaces.status", "publish");
        }

        if ($statuses = $request->query('active')) {
            $modelMarketplace->whereIn("bc_marketplaces.status", [$statuses]);
        }

        if ($sponsored = $request->query('sponsored')) {
            $modelMarketplace->whereIn("bc_marketplaces.is_featured", $sponsored);
        }

        if ($trainingLocation = $request->get('announcement_status')) {
            $modelMarketplace->where(static function(Builder $builder) use ($trainingLocation) {
                foreach ($trainingLocation as $item) {
                    $builder->orWhere('bc_marketplaces.announcement_status', 'LIKE', "%{$item}%");
                }
            });
        }

        if (($locationName = $request->get('location'))) {
            static::searchByLocation($modelMarketplace, 'bc_marketplaces', $locationName);
        }

        $categorySlug = $request->query('category') ?? $request->category;
        if (!empty($categorySlug)) {
            $modelMarketplace->join('bc_marketplace_cat', 'bc_marketplaces.cat_id', '=', 'bc_marketplace_cat.id')
                ->where('bc_marketplace_cat.slug', '=', $categorySlug);
        }
//        if (!empty($priFrom = $request->query('price_from')) && !empty($priTo = $request->query('price_to'))) {
//            $rawSqlMinMax = "( bc_marketplaces.price >= ?)
//                            AND (IFNULL(bc_marketplaces.price, 0) <= ? )";
//            $modelMarketplace->WhereRaw($rawSqlMinMax, [$priFrom, $priTo]);
//        }

        if (!empty($keywords = $request->query("s"))) {
            static::searchByKeywords('where', $modelMarketplace, 'bc_marketplaces', 'title', $keywords);
        }

        if (!empty($orderby = $request->query("orderby"))) {
            switch ($orderby) {
                case"new":
                    $modelMarketplace->orderBy("bc_marketplaces.id", "desc");
                    break;
                case"old":
                    $modelMarketplace->orderBy("bc_marketplaces.id", "asc");
                    break;
                case"name_high":
                    $modelMarketplace->orderBy("bc_marketplaces.title", "asc");
                    break;
                case"name_low":
                    $modelMarketplace->orderBy("bc_marketplaces.title", "desc");
                    break;
                default:
                    $modelMarketplace->orderBy("bc_marketplaces.is_featured", "desc");
                    $modelMarketplace->orderBy("bc_marketplaces.id", "desc");
                    break;
            }
        } else {
            $modelMarketplace->orderBy("bc_marketplaces.is_featured", "desc");
            $modelMarketplace->orderBy("bc_marketplaces.id", "desc");
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
                $modelMarketplace->where('bc_marketplaces.created_at', '>=', $date_p);
            }

            if (!empty($dateFrom) && !empty($dateTo)) {
                $modelMarketplace->whereBetween('bc_marketplaces.created_at', [$dateFrom, $dateTo]);
            }
        }

        $modelMarketplace->groupBy("bc_marketplaces.id");

        $maxGuests = (int)($request->query('adults') + $request->query('children'));
        if ($maxGuests) {
            $modelMarketplace->where('max_guests', '>=', $maxGuests);
        }

        if ($request->query("count")) {
            return $modelMarketplace->count();
        }

        $limit = $request->query('limit', $defaultListCountSearch);

        return $modelMarketplace->with(['author', 'company', 'location', 'translations', 'MarketplaceCategory'])->paginate($limit);
    }

    public function getNumberWishlistInService($status = false)
    {
        return $this->hasOne($this->userWishListClass, 'object_id', 'id')->where('object_model', $this->type)->count();
    }

    static public function getFiltersSearch()
    {
        $min_max_price = self::getMinMaxPrice();
        return [
            [
                "title" => __("Filter Price"),
                "field" => "price_range",
                "position" => "1",
                "min_price" => floor(Currency::convertPrice($min_max_price[0])),
                "max_price" => ceil(Currency::convertPrice($min_max_price[1])),
            ],
            [
                "title" => __("Review Score"),
                "field" => "review_score",
                "position" => "2",
                "min" => "1",
                "max" => "5",
            ],
            [
                "title" => __("Attributes"),
                "position" => "3",
                "data" => Attributes::getAllAttributesForApi("event")
            ]
        ];
    }


    public function saveTag($tags_name, $tag_ids)
    {

        if (empty($tag_ids))
            $tag_ids = [];
        $tag_ids = array_merge(Tag::saveTagByName($tags_name), $tag_ids);
        $tag_ids = array_filter(array_unique($tag_ids));
        // Delete unused
        MarketplaceTag::query()->whereNotIn('tag_id', $tag_ids)->where('target_id', $this->id)->delete();
        //Add
        MarketplaceTag::addTag($tag_ids, $this->id);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function MarketplaceCategory()
    {
        return $this->belongsTo(MarketplaceCategory::class, 'cat_id');
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function getTags()
    {
        $tags = MarketplaceTag::where('target_id', $this->id)->get();
        $tag_ids = [];
        if (!empty($tags)) {
            foreach ($tags as $key => $value) {
                $tag_ids[] = $value->tag_id;
            }
        }
        return Tag::whereIn('id', $tag_ids)->with('translations')->get();
    }

    public function getImageUrl($size = "medium", $img = '')
    {
        $s_image = (!empty($img)) ? $img : $this->image_id;
        $url = FileHelper::url($s_image, $size);
        return $url ? $url : '';
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

    public function dataForApi($forSingle = false)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'slug' => $this->slug,
            'title' => $this->title,
            'price' => $this->price,
            'is_featured' => $this->is_featured,
//            'wish_list' => $user ? $user->hasWishList($this->type, $this->id)->exists() : false,
            'image_url' => empty($this->image_id) ?: get_file_url($this->image_id),
            'url' => $this->getDetailUrl(),
            'location' => $this->location ? [
                'name' => $this->location->map_location
            ] : null,
            'company' => !empty($this->company) ? $this->company
                ->only(['id', 'name', 'avatar_url']) : null,
        ];
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

    public function orderItem()
    {
        return $this->hasOne(OrderItem::class, 'meta->model_id', 'id');
    }

    public function publish(?int $expirationDays = null, bool $isPackage = false): void
    {
        $this->update([
            'status'          => self::STATUS_PUBLISH,
            'expiration_date' => $expirationDays ? Carbon::now()->endOfDay()->addDays($expirationDays) : null,
            'is_package'      => $isPackage
        ]);
    }
}
