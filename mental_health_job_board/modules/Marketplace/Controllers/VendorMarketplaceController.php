<?php

namespace Modules\Marketplace\Controllers;

use App\Notifications\AdminChannelServices;
use Modules\Booking\Marketplace\BookingUpdatedMarketplace;
use Modules\Core\Marketplace\CreatedServicesMarketplace;
use Modules\Core\Marketplace\UpdatedServiceMarketplace;
use Modules\Marketplace\Exports\MarketplaceExport;
use Modules\Marketplace\Models\Marketplace;
use Modules\Marketplace\Models\MarketplaceCategory;
use Modules\Marketplace\Models\MarketplaceTranslation;
use Modules\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Location\Models\Location;
use Modules\Core\Models\Attributes;
use Modules\Booking\Models\Booking;
use Modules\Location\Models\LocationCategory;
use Modules\Location\Services\LocationService;

class VendorMarketplaceController extends FrontendController
{
    protected $MarketplaceClass;
    protected $MarketplaceTranslationClass;
    protected $attributesClass;
    protected $locationClass;
    protected $bookingClass;
    protected $CategoryClass;
    /**
     * @var string
     */
    private $locationCategoryClass;

    public function __construct()
    {
        parent::__construct();
        $this->MarketplaceClass = Marketplace::class;
        $this->MarketplaceTranslationClass = MarketplaceTranslation::class;
        $this->attributesClass = Attributes::class;
        $this->locationClass = Location::class;
        $this->bookingClass = Booking::class;
        $this->CategoryClass = MarketplaceCategory::class;
    }

    public function callAction($method, $parameters)
    {
        if (!Marketplace::isEnable()) {
            return redirect('/');
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function indexMarketplace(Request $request)
    {
        $this->checkPermission('marketplace_manage');
        $user_id = Auth::id();
        $list_tour = Marketplace::withTrashed()->where("create_user", $user_id)->orderBy('id', 'desc');
        if ($request->input('s')) {
            $list_tour->where('title', 'like', '%' . $request->input('s') . '%');
        }
        $data = [
            'rows'        => $list_tour->paginate(20),
            'breadcrumbs' => [
                [
                    'name' => __('Manage Marketplaces'),
                    'url'  => route('marketplace.vendor.index')
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title'  => __("Manage Marketplaces"),
        ];
        return view('Marketplace::frontend.seller.manageMarketplace.index', $data);
    }

    public function recovery(Request $request)
    {
        $this->checkPermission('marketplace_manage');
        $user_id = Auth::id();
        $list_tour = $this->MarketplaceClass::onlyTrashed()->where("create_user", $user_id)->orderBy('id', 'desc');
        $data = [
            'rows'        => $list_tour->paginate(5),
            'recovery'    => 1,
            'breadcrumbs' => [
                [
                    'name' => __('Manage Marketplaces'),
                    'url'  => route('marketplace.vendor.index')
                ],
                [
                    'name'  => __('Recovery'),
                    'class' => 'active'
                ],
            ],
            'page_title'  => __("Recovery Marketplaces"),
        ];
        return view('Marketplace::frontend.vendorMarketplace.index', $data);
    }

    public function restore($id)
    {
        $this->checkPermission('marketplace_manage');
        $user_id = Auth::id();
        $query = $this->MarketplaceClass::onlyTrashed()->where("create_user", $user_id)->where("id", $id)->first();
        if (!empty($query)) {
            $query->restore();
        }
        return redirect(route('marketplace.vendor.recovery'))->with('success', __('Restore event success!'));
    }

    public function createMarketplace(Request $request)
    {
        $this->checkPermission('marketplace_manage');
        $row = new $this->MarketplaceClass();
        $data = [
            'row'                  => $row,
            'translation'          => new $this->MarketplaceTranslationClass(),
            'marketplace_location' => $this->locationClass::query()->where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'categories'           => $this->CategoryClass::where('status', 'publish')->get(),
            'attributes'           => $this->attributesClass::where('service', 'Marketplace')->get(),
            'breadcrumbs'          => [
                [
                    'name' => __('Manage Marketplaces'),
                    'url'  => route('marketplace.vendor.index')
                ],
                [
                    'name'  => __('Create'),
                    'class' => 'active'
                ],
            ],
            'page_title'           => __("Create Marketplaces"),
        ];
        return view('Marketplace::frontend.seller.manageMarketplace.edit', $data);
    }


    public function store(Request $request, $id)
    {
        if ($id > 0) {
            $this->checkPermission('marketplace_manage');
            $row = $this->MarketplaceClass::find($id);
            if (empty($row)) {
                return redirect(route('Marketplace.vendor.index'));
            }

            if ($row->create_user != Auth::id() and !$this->hasPermission('marketplace_manage_others')) {
                return redirect(route('marketplace.vendor.index'));
            }
        } else {
            $this->checkPermission('marketplace_manage');
            $row = new $this->MarketplaceClass();
            $row->status = "publish";
            if (setting_item("Marketplace_vendor_create_service_must_approved_by_admin", 0)) {
                $row->status = "pending";
            }
        }
        $dataKeys = [
            'title',
            'content',
            'video',
            'image_id',
            'banner_image_id',
            'gallery',
            'location_id',
            'map_lat',
            'map_lng',
            'map_zoom',
            'is_featured',
            'default_state',
        ];
        if ($this->hasPermission('marketplace_manage_others')) {
            $dataKeys[] = 'create_user';
        }

        $row->fillByAttr($dataKeys, $request->input());

        $res = $row->saveOriginOrTranslation($request->input('lang'), true);

        if ($res) {
            if ($id > 0) {
                event(new UpdatedServiceMarketplace($row));

                return back()->with('success', __('Your Marketplace post has been updated'));
            } else {
                event(new CreatedServicesMarketplace($row));
                return redirect(route('Marketplace.vendor.edit', ['id' => $row->id]))->with('success', __('Your Marketplace post has been created'));
            }
        }
    }

    public function editMarketplace(Request $request, $id)
    {
        $this->checkPermission('marketplace_manage');
        $user_id = Auth::id();
        $row = $this->MarketplaceClass::where("create_user", $user_id);
        $row = $row->find($id);
        if (empty($row)) {
            return redirect(route('Marketplace.vendor.index'))->with('warning', __('Marketplace not found!'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        $data = [
            'translation'          => $translation,
            'row'                  => $row,
            'Marketplace_location' => $this->locationClass::where("status", "publish")->get()->toTree(),
            'location_category'    => $this->locationCategoryClass::where('status', 'publish')->get(),
            'attributes'           => $this->attributesClass::where('service', 'Marketplace')->get(),
            'breadcrumbs'          => [
                [
                    'name' => __('Manage Marketplaces'),
                    'url'  => route('Marketplace.vendor.index')
                ],
                [
                    'name'  => __('Edit'),
                    'class' => 'active'
                ],
            ],
            'page_title'           => __("Edit Marketplaces"),
        ];
        return view('Marketplace::frontend.vendorMarketplace.detail', $data);
    }

    public function deleteMarketplace($id)
    {
        $this->checkPermission('marketplace_manage');
        $user_id = Auth::id();
        if (\request()->query('permanently_delete')) {
            $query = $this->MarketplaceClass::where("create_user", $user_id)->where("id", $id)->withTrahsed()->first();
            if (!empty($query)) {
                $query->forceDelete();
            }
        } else {
            $query = $this->MarketplaceClass::where("create_user", $user_id)->where("id", $id)->first();
            if (!empty($query)) {
                $query->delete();
                event(new UpdatedServiceMarketplace($query));
            }
        }
        return redirect(route('Marketplace.vendor.index'))->with('success', __('Delete event success!'));
    }

    public function bulkEditMarketplace($id, Request $request)
    {
        $this->checkPermission('marketplace_manage');
        $action = $request->input('action');
        $user_id = Auth::id();
        $query = $this->MarketplaceClass::where("create_user", $user_id)->where("id", $id)->first();
        if (empty($id)) {
            return redirect()->back()->with('error', __('No item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }
        if (empty($query)) {
            return redirect()->back()->with('error', __('Not Found'));
        }
        switch ($action) {
            case "make-hide":
                $query->status = "draft";
                break;
            case "make-publish":
                $query->status = "publish";
                break;
        }
        $query->save();
        event(new UpdatedServiceMarketplace($query));

        return redirect()->back()->with('success', __('Update success!'));
    }

    public function bookingReportBulkEdit($booking_id, Request $request)
    {
        $status = $request->input('status');
        if (!empty(setting_item("Marketplace_allow_vendor_can_change_their_booking_status")) and !empty($status) and !empty($booking_id)) {
            $query = $this->bookingClass::where("id", $booking_id);
            $query->where("vendor_id", Auth::id());
            $item = $query->first();
            if (!empty($item)) {
                $item->status = $status;
                $item->save();

                if ($status == Booking::CANCELLED) $item->tryRefundToWallet();

                event(new BookingUpdatedMarketplace($item));
                return redirect()->back()->with('success', __('Update success'));
            }
            return redirect()->back()->with('error', __('Booking not found!'));
        }
        return redirect()->back()->with('error', __('Update fail!'));
    }

    public function MarketplaceExport()
    {
        $this->checkPermission('marketplace_manage');

        return (new MarketplaceExport())->download('Marketplace-' . date('M-d-Y') . '.xlsx');
    }
}
