<?php

namespace Modules\Equipment\Models;

use App\Currency;
use App\User;
use Carbon\Carbon;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\Support\Period;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Booking\Models\Bookable;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingTimeSlots;
use Modules\Company\Models\Company;
use Modules\Core\Models\Attributes;
use Modules\Location\Models\Location;
use Modules\Media\Helpers\FileHelper;
use Modules\News\Models\Tag;
use Modules\Order\Helpers\CartManager;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Review\Models\Review;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\UserWishList;

class Equipment extends Bookable implements Viewable
{
    use Notifiable;
    use SoftDeletes;
    use InteractsWithViews;

    protected $table = 'bc_equipments';
    public $type = 'equipment';

    protected $fillable = [
        'title',
        'content',
        'status',
        'company_id',
        'map_lat',
        'map_lng',
        'map_zoom',
        'location_id',
        'expiration_date',
        'is_featured'
    ];
    protected $slugField = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type = 'equipment';
    protected $casts = [
        'packages' => 'array',
        'extra_price' => 'array',
        'package_compare' => 'array',
        'requirements' => 'array',
        'faqs' => 'array',
        'map_lat' => 'float',
        'map_lng' => 'float'
    ];
    protected $bookingClass;
    protected $bookingTimeSlotsClass;
    protected $reviewClass;
    protected $equipmentDateClass;
    protected $equipmentTranslationClass;
    protected $userWishListClass;
    protected $locationClass;

    const BASIC_EXPIRATION_DAYS = 20;

    const STATUS_PUBLISH = 'publish';

    protected $attributes = [
        'status' => 'draft'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->bookingClass = Booking::class;
        $this->bookingTimeSlotsClass = BookingTimeSlots::class;
        $this->reviewClass = Review::class;
        $this->equipmentTranslationClass = EquipmentTranslation::class;
        $this->userWishListClass = UserWishList::class;
        $this->locationClass = Location::class;
    }

    public static function getModelName()
    {
        return __("equipment");
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
        $meta['seo_title'] = __("Search for equipments");
        if (!empty($title = setting_item_with_lang("equipment_page_list_seo_title", false))) {
            $meta['seo_title'] = $title;
        } else if (!empty($title = setting_item_with_lang("equipment_page_search_title"))) {
            $meta['seo_title'] = $title;
        }
        $meta['seo_image'] = null;
        if (!empty($title = setting_item("equipment_page_list_seo_image"))) {
            $meta['seo_image'] = $title;
        } else if (!empty($title = setting_item("equipment_page_search_banner"))) {
            $meta['seo_image'] = $title;
        }
        $meta['seo_desc'] = setting_item_with_lang("equipment_page_list_seo_desc");
        $meta['seo_share'] = setting_item_with_lang("equipment_page_list_seo_share");
        $meta['full_url'] = url(env('equipment_ROUTE_PREFIX', 'equipment'));
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
        $urlDetail = app_get_locale(false, false, '/') . env('equipment_ROUTE_PREFIX', 'equipment') . "/" . $this->slug;
        if (!empty($param)) {
            $urlDetail .= "?" . http_build_query($param);
        }
        return url($urlDetail);
    }

    public static function getLinkForPageSearch($locale = false, $param = [])
    {

        return url(app_get_locale(false, false, '/') . env('equipment_ROUTE_PREFIX', 'equipment') . "?" . http_build_query($param));
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

    public function getEditUrl()
    {
        return url(route('equipment.admin.edit', ['id' => $this->id]));
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
        return setting_item("equipment_enable_review", 0);
    }

    public function getReviewApproved()
    {
        return setting_item("equipment_review_approved", 0);
    }

    public static function getReviewStats()
    {
        $reviewStats = [];
        if (!empty($list = setting_item("equipment_review_stats", []))) {
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
        $equipment_id = $this->id;
        $list_score = Cache::rememberForever('review_' . $this->type . '_' . $equipment_id, function () use ($equipment_id) {
            $dataReview = $this->reviewClass::selectRaw(" AVG(rate_number) as score_total , COUNT(id) as total_review ")->where('object_id', $equipment_id)->where('object_model', $this->type)->where("status", "approved")->first();
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
        return $this->reviewClass::select(['id', 'title', 'content', 'rate_number', 'author_ip', 'status', 'created_at', 'vendor_id', 'create_user'])->where('object_id', $this->id)->where('object_model', 'equipment')->where("status", "approved")->orderBy("id", "desc")->with('author')->paginate(setting_item('equipment_review_number_per_page', 5));
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
            return __(":number equipments", ['number' => $number]);
        }
        return __(":number equipment", ['number' => $number]);
    }

    public static function getServiceIconFeatured()
    {
        return "flaticon-climbing";
    }

    public static function isEnable()
    {
        return setting_item('equipment_disable') == false;
    }

    public function isDepositEnable()
    {
        return (setting_item('equipment_deposit_enable') and setting_item('equipment_deposit_amount'));
    }

    public function getDepositAmount()
    {
        return setting_item('equipment_deposit_amount');
    }

    public function getDepositType()
    {
        return setting_item('equipment_deposit_type');
    }

    public function getDepositFomular()
    {
        return setting_item('equipment_deposit_fomular', 'default');
    }


    public static function search(Request $request, $authorId = null)
    {
        $modelEquipment = parent::query()->select("bc_equipments.*");

        if (!is_null($authorId)) {
            $modelEquipment->where('bc_equipments.author_id', $authorId);
        } else {
            $modelEquipment->where("bc_equipments.status", "publish");
        }

        if ($statuses = $request->query('active')) {
            $modelEquipment->whereIn("bc_equipments.status", [$statuses]);
        }

        if ($sponsored = $request->query('sponsored')) {
            $modelEquipment->whereIn("bc_equipments.is_featured", $sponsored);
        }

        if (($locationName = $request->get('location')) &&
            !empty($locationType =  $request->get('location_type'))
        ) {
            static::searchByLocation($modelEquipment, 'bc_equipments', $locationName, $locationType, $request->get('location_state'));
        }

        $categorySlug = $request->query('category') ?? $request->category;
        if (!empty($categorySlug)) {
            $modelEquipment->join('bc_equipment_cat', 'bc_equipments.cat_id', '=', 'bc_equipment_cat.id')
                ->whereIn('bc_equipment_cat.slug', $categorySlug);
        }

        if (!empty($priFrom = $request->query('price_from')) && !empty($priTo = $request->query('price_to'))) {
            $rawSqlMinMax = "( bc_equipments.price >= ?)
                            AND (IFNULL(bc_equipments.price, 0) <= ? )";
            $modelEquipment->WhereRaw($rawSqlMinMax, [$priFrom, $priTo]);
        }

        $reviewScores = $request->query('review_score');
        if (is_array($reviewScores) && !empty($reviewScores)) {
            $whereReviewScore = [];
            $params = [];
            foreach ($reviewScores as $number) {
                $whereReviewScore[] = " ( bc_equipments.review_score >= ? AND bc_equipments.review_score <= ? ) ";
                $params[] = $number;
                $params[] = $number . '.9';
            }
            $sql_where_review_score = " ( " . implode("OR", $whereReviewScore) . " )  ";
            $modelEquipment->WhereRaw($sql_where_review_score, $params);
        }

        if (!empty($keywords = $request->query("keywords"))) {
            static::searchByKeywords('where', $modelEquipment, 'bc_equipments', 'title', $keywords);
        }
        if (!empty($lat = $request->query('map_lat')) and !empty($lgn = $request->query('map_lgn'))) {
            $modelEquipment->orderByRaw("POW((bc_equipments.map_lng-?),2) + POW((bc_equipments.map_lat-?),2)", [$lgn, $lat]);
        }

        if (!empty($deliveryTime = $request->query("delivery_time")) and $deliveryTime != "any_time") {
            $modelEquipment->where('bc_equipments.basic_delivery_time', '<=', $deliveryTime);
        }

        if (!empty($orderby = $request->query("orderby"))) {
            switch ($orderby) {
                case"new":
                    $modelEquipment->orderBy("bc_equipments.id", "desc");
                    break;
                case"old":
                    $modelEquipment->orderBy("bc_equipments.id", "asc");
                    break;
                case"name_high":
                    $modelEquipment->orderBy("bc_equipments.title", "asc");
                    break;
                case"name_low":
                    $modelEquipment->orderBy("bc_equipments.title", "desc");
                    break;
                default:
                    $modelEquipment->orderBy("bc_equipments.is_featured", "desc");
                    $modelEquipment->orderBy("bc_equipments.id", "desc");
                    break;
            }
        } else {
            $modelEquipment->orderBy("bc_equipments.is_featured", "desc");
            $modelEquipment->orderBy("bc_equipments.id", "desc");
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
                $modelEquipment->where('bc_equipments.created_at', '>=', $date_p);
            }

            if (!empty($dateFrom) && !empty($dateTo)) {
                $modelEquipment->whereBetween('bc_equipments.created_at', [$dateFrom, $dateTo]);
            }
        }

        $modelEquipment->groupBy("bc_equipments.id");

        $maxGuests = (int)($request->query('adults') + $request->query('children'));
        if ($maxGuests) {
            $modelEquipment->where('max_guests', '>=', $maxGuests);
        }

        if ($request->query("count")) {
            return $modelEquipment->count();
        }

        $limit = $request->query('limit', 10);

        return $modelEquipment->with(['author', 'company', 'location', 'translations', 'equipmentCategory'])->paginate($limit);
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
        EquipmentTag::query()->whereNotIn('tag_id', $tag_ids)->where('target_id', $this->id)->delete();
        //Add
        EquipmentTag::addTag($tag_ids, $this->id);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function equipmentCategory()
    {
        return $this->belongsTo(EquipmentCategory::class, 'cat_id');
    }

    public function cat2()
    {
        return $this->belongsTo(EquipmentCategory::class, 'cat2_id');
    }

    public function cat3()
    {
        return $this->belongsTo(EquipmentCategory::class, 'cat3_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function getTags()
    {
        $tags = EquipmentTag::where('target_id', $this->id)->get();
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

    public function dataForApi($user, $forSingle = false)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'slug' => $this->slug,
            'title' => $this->title,
            'price' => $this->price,
            'is_featured' => $this->is_featured,
            'wish_list' => $user ? $user->hasWishList($this->type, $this->id)->exists() : false,
            'image_url' => empty($this->image_id) ?: get_file_url($this->image_id),
            'url' => $this->getDetailUrl(),
            'location' => $this->location ? [
                'name' => $this->location->map_location
            ] : null,
            'company' => !empty($this->company) ? $this->company
                ->only(['id', 'name', 'avatar_url']) : null,
        ];
    }

    public function publish($params = [])
    {
        return $this->update(array_merge([
            'status' => self::STATUS_PUBLISH,
            'expiration_date' => Carbon::now()->addDays(self::BASIC_EXPIRATION_DAYS),
        ], $params));
    }

    public function sponsored()
    {
        return $this->publish(['is_featured' => true]);
    }
}
