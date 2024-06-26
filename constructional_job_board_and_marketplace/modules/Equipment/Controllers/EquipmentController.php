<?php

namespace Modules\Equipment\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BasicFilterService;
use App\Services\PopularSearchService;
use Illuminate\Support\Facades\App;
use Modules\Equipment\Resources\CategoryResource;
use Modules\Equipment\Resources\EquipmentDetailResource;
use Modules\Equipment\Resources\EquipmentListResource;
use Modules\Media\Helpers\FileHelper;
use Modules\Page\Models\Page;
use Validator;
use Illuminate\Support\Facades\Auth;
use Modules\Equipment\Models\Equipment;
use Illuminate\Http\Request;
use Modules\Equipment\Models\EquipmentCategory;
use Modules\Location\Models\Location;
use DB;

class EquipmentController extends Controller
{
    protected $equipmentClass;
    protected $locationClass;
    /**
     * @var string
     */
    private $locationCategoryClass;

    public function __construct()
    {
        $this->equipmentClass = Equipment::class;
        $this->locationClass = Location::class;
    }

    public function callAction($method, $parameters)
    {
        if (!Equipment::isEnable()) {
            return redirect('/');
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function index(Request $request, PopularSearchService $popularSearchService, BasicFilterService $basicFilterService)
    {
        $popularSearchService->store($request);

        $equipments = call_user_func([Equipment::class, 'search'],  $request);

        $page = Page::where("slug", 'equipment')->where("status", "publish")->first();

        $data = [
            'items' => EquipmentListResource::collection($equipments),
            'filters' => [
                'category' => [
                    'items' => CategoryResource::collection(EquipmentCategory::where('status', 'publish')->withCount('openEquipment')->get()->sortBy('name')->toTree()),
                    'values' => $request->category,
                ],
                'price' => [
                    'items' => $this->equipmentClass::getMinMaxPrice(),
                    'values' => [
                        'price_from' => $request->price_from,
                        'price_to' => $request->price_to,
                    ]
                ],
                'keywords' => $basicFilterService->popularSearchKeywords($request->keywords, App::make($this->equipmentClass)->type),
                'location' => $basicFilterService->popularSearchLocation($request->location, App::make($this->equipmentClass)->type),
                'location_type' => $basicFilterService->searchLocationType($request->location_type),
                'location_state' => $basicFilterService->searchLocationState($request->location_state),
                'orderby' => $basicFilterService->orderby($request->orderby),
                'limit' => $basicFilterService->limit($request->limit),
            ],
            "seo_meta" => getSeoData($this->equipmentClass)
        ];

        return inertia('Equipment/Index', $data);
    }

    public function category($slug)
    {
        $category = EquipmentCategory::query()->withDepth()->where('slug', $slug)->where('status', 'publish')->first();
        if (!$category) {
            abort(404);
        }
        $data = [
            'page' => [],
            'category' => $category,
            'translation' => $category->translateOrOrigin(app()->getLocale()),
            'page_title' => $category->name,
            'min_max_price' => Equipment::getMinMaxPrice(),
        ];

        switch ($category->depth) {
            case 0:
                return view('Equipment::frontend.category_lv1', $data);
                break;
            default:
                $filters = \request()->query();
                if ($category->depth == 1) {
                    $filters['cat2_id'] = $category->id;
                }
                if ($category->depth == 2) {
                    $filters['cat3_id'] = $category->id;
                }
                $data['rows'] = Equipment::search($filters)->with(['author'])->paginate(setting_item('equipment_page_limit_item', 24));
                return view('Equipment::frontend.category', $data);
                break;
        }
    }

    public function detail(Request $request, $slug)
    {
        $equipment = $this->equipmentClass::where('slug', $slug)->with(['location', 'company', 'equipmentCategory'])->first();

        views($equipment)->record();

        if (empty($equipment) or !$equipment->hasPermissionDetailView()) {
            return redirect('/');
        }

        $equipmentRelated = [];
        $categoryId = $equipment->cat_id;
        if (!empty($categoryId)) {
            $equipmentRelated = $this->equipmentClass::with(['translations'])->where('cat_id', $categoryId)->where("status", "publish")->whereNotIn('id', [$equipment->id])->take(3)->get();
        }

        $data = [
            'equipment' => new EquipmentDetailResource($equipment),
            'equipment_related' => EquipmentListResource::collection($equipmentRelated),
            'seo_meta' => $equipment->getSeoMeta(),
        ];
        $this->setActiveMenu($equipment);
        return inertia('Equipment/Detail', $data);
    }

    public function EquipmentSearchCount(Request $request)
    {
        return call_user_func([Equipment::class, 'search'],  $request);
    }
}
