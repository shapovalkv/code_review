<?php

namespace Modules\Marketplace\Controllers;

use App\Enums\UserPermissionEnum;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Core\Events\CreatedServicesEvent;
use Modules\Core\Events\UpdatedServiceEvent;
use Modules\Marketplace\Exports\MarketplaceExport;
use Modules\Marketplace\Requests\BulkMarketplaceRequest;
use Modules\Marketplace\Requests\CreateMarketplaceRequest;
use Modules\Marketplace\Requests\UpdateMarketplaceRequest;
use Modules\Marketplace\Resources\CategoryResource;
use Modules\Marketplace\Resources\MarketplaceManageResource;
use Modules\FrontendController;
use Modules\Marketplace\Models\Marketplace;
use Modules\Core\Models\Attributes;
use Modules\Marketplace\Models\MarketplaceCategory;
use Modules\Marketplace\Models\MarketplaceTranslation;
use Modules\Location\Models\Location;
use Modules\Marketplace\Services\MarketPlaceService;
use Modules\Order\Helpers\CartManager;
use Modules\User\Models\Plan;
use Modules\User\Models\PlanFeature;
use Modules\User\Models\Role;
use Modules\User\Models\UserPlan;

class MarketplaceManageController extends FrontendController
{
    protected $MarketplaceClass;

    public function __construct()
    {
        parent::__construct();
        $this->MarketplaceClass = Marketplace::class;
    }

    public function callAction($method, $parameters)
    {
        if (!Marketplace::isEnable()) {
            return redirect('/');
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function index(Request $request)
    {
        $this->checkPermission('marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE);

        $authorId = auth()->user()->parent ? auth()->user()->parent->id : auth()->user()->id ?? null;

        $builder = Marketplace::query()->where('author_id', $authorId);

        if($request->input('s')) {
            $builder->where('title', 'like', '%'.$request->input('s').'%');
        }
        $Marketplaces = $builder->orderByDesc('created_at')->orderByDesc('created_at')->paginate();
        $data = [
            'rows'        => MarketplaceManageResource::collection($Marketplaces),
            'pagination'  => [
                'total'        => $Marketplaces->total(),
                'last_page'    => $Marketplaces->lastPage(),
                'per_page'     => $Marketplaces->perPage(),
                'current_page' => $Marketplaces->currentPage(),
            ],
            'filters'     => [
                'category' => [
                    'items'  => CategoryResource::collection(MarketplaceCategory::where('status', 'publish')->withCount('openMarketplace')->get()->sortBy('name')->toTree()),
                    'values' => $request->category,
                ],
            ],
            'menu_active' => 'seller_Marketplaces',
            'page_title'  => __("All Marketplace"),
        ];

        return view('Marketplace::frontend.seller.manageMarketplace.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $this->checkPermission('marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE);

        $user = auth()->user()->parent ? auth()->user()->parent : auth()->user();

        $row = Marketplace::query()->where('author_id', $user->id)->find($id);

        if (empty($row)) {
            return redirect(route('seller.all.marketplaces'));
        }

        $translation = $row->translateOrOrigin($request->query('lang'));

        $data = [
            'row'                  => $row,
            'translation'          => $translation,
            'attributes'           => Attributes::where('service', 'Marketplace')->get(),
            'marketplace_location' => Location::query()->where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'categories'           => MarketplaceCategory::query()->where('status', 'publish')->get()->sortBy('name')->toTree(),
            'enable_multi_lang'    => true,
            'tags'                 => $row->getTags(),
            'page_title'           => __("Edit Marketplace"),
            'menu_active'          => 'seller_marketplaces',
            'is_user_page'         => true
        ];

        return view('Marketplace::frontend.seller.manageMarketplace.edit', $data);
    }

    public function create(Request $request, MarketPlaceService $marketPlaceService)
    {
        $this->checkPermission('marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE);

        if ($request->get('cache') === 'clear') {
            Cache::forget(auth()->id() . Marketplace::CACHE_KEY_DRAFT);

            return redirect()->route('seller.marketplace.create');
        }

        $row = new Marketplace();
        $row->fill([
            'status' => 'publish'
        ]);

        $data = [
            'row'                  => $marketPlaceService->fillByAttrForCreateMarketplace($row),
            'tags'                 => $row->getTags(),
            'attributes'           => Attributes::where('service', 'Marketplace')->get(),
            'marketplace_location' => Location::query()->where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'categories'           => MarketplaceCategory::query()->where('status', 'publish')->get()->sortBy('name')->toTree(),
            'translation'          => new MarketplaceTranslation(),
            'locations'            => Location::query()->where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'page_title'           => __("Post on Marketplace"),
            'is_user_page'         => true
        ];

        return view('Marketplace::frontend.seller.manageMarketplace.edit', $data);
    }

    public function update(UpdateMarketplaceRequest $request, Marketplace $marketplace): RedirectResponse
    {
        $this->checkPermission('marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE);

        $dataKeys = [
            'title',
            'content',
            'cat_id',
            'website',
            'video_url',
            'video_cover_image_id',
            'image_id',
            'gallery',
            'thumbnail_id',
            'location_id',
            'map_lat',
            'map_lng',
            'map_zoom',
        ];

        $marketplace->fillByAttr($dataKeys, $request->input());
        $marketplace->announcement_status = json_encode($request->input('announcement_status'));
        if (!empty($request->input('announcement_date'))) {
            $marketplace->announcement_date = Carbon::createFromFormat(get_date_format(), $request->input('announcement_date'));
        }

        $save = $marketplace->saveOriginOrTranslation($request->input('lang'), true);

        if ($save) {
            if (is_default_lang($request->query('lang'))) {
                $marketplace->saveTag($request->input('tag_name'), $request->input('tag_ids'));
            }

            if (
                false === $marketplace->isActive()
                && $request->input('status') === Marketplace::STATUS_PUBLISH
                && (empty($marketplace->orderItem) || $marketplace->orderItem->status !== 'completed')
                && !is_admin()
            ) {
                $marketplace->status = Marketplace::STATUS_DRAFT;
                $marketplace->save();

                return redirect(route('seller.choose.marketplace.plan', ['marketplace' => $marketplace->id]))->with('error', __('Sponsor your Announcement to publish'));
            }

            if (is_admin()){
                $marketplace->publish(Marketplace::BASIC_EXPIRATION_DAYS);
            }

            $marketplace->status = $request->input('status');
            $marketplace->save();

            event(new UpdatedServiceEvent($marketplace));

            return back()->with('success', __('Your Announcement post has been updated'));
        }

        return back()->with('error', __('Your Announcement post has not been updated'));
    }

    public function store(CreateMarketplaceRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user()->parent ?? auth()->user();

        $this->checkPermission('marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE);
        $marketplace = new Marketplace();
        $marketplace->status = is_admin() ? $request->input('status') : Marketplace::STATUS_DRAFT;
        $marketplace->is_featured = 0;

        $dataKeys = [
            'title',
            'content',
            'cat_id',
            'website',
            'video_url',
            'video_cover_image_id',
            'thumbnail_id',
            'image_id',
            'gallery',
            'location_id',
            'map_lat',
            'map_lng',
            'map_zoom',
        ];
        $marketplace->fillByAttr($dataKeys, $request->input());
        if (!empty($request->input('announcement_date'))) $marketplace->announcement_date = Carbon::createFromFormat(get_date_format(), $request->input('announcement_date'));
        $marketplace->announcement_status = json_encode($request->input('announcement_status'));
        $marketplace->author_id = $user->id;
        $company = User::find($marketplace->author_id)->company;
        $marketplace->company_id = $company ? $company->id : null;

        if ($request->input('slug')) {
            $marketplace->slug = $request->input('slug');
        }

        $save = $marketplace->saveOriginOrTranslation($request->input('lang'), true);

        Cache::forget(auth()->id() . Marketplace::CACHE_KEY_DRAFT);

        if ($save) {
            if (is_default_lang($request->query('lang'))) {
                $marketplace->saveTag($request->input('tag_name'), $request->input('tag_ids'));
            }

            event(new CreatedServicesEvent($marketplace));
            if (is_admin()) {
                $marketplace->publish(Marketplace::BASIC_EXPIRATION_DAYS);
                $marketplace->save();

                return redirect(route('seller.marketplace.edit', ['id' => $marketplace->id]))->with('info', __('Your Announcement post has been created.'));
            } else {
                if ($request->input('status') === Marketplace::STATUS_PUBLISH) {
                    $marketplace->status = Marketplace::STATUS_DRAFT;
                    $marketplace->save();
                    return redirect(route('seller.choose.marketplace.plan', ['marketplace' => $marketplace->id]))->with('info', __('Your Announcement post has been created but is in draft status. Sponsor your Announcement to publish.'));
                } else {
                    return redirect(route('seller.marketplace.edit', ['id' => $marketplace->id]))->with('info', __('Your Announcement post has been created but not published!'));
                }
            }
        }

        return back()->with('error', __('Your Announcement post has not been crteated'));
    }

    public function choosePlan(Marketplace $marketplace, MarketPlaceService $marketPlaceService): View|RedirectResponse
    {
        $this->checkPermission('marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE);

        if ($marketplace->isActive()) {
            return redirect()->back();
        }

        /** @var User $user */
        $user = auth()->user()->parent ?? auth()->user();

        $userPlan = $user->currentUserPlan;
        $userPlan?->load(UserPlan::RELATION_PLAN);

        $announcementInThisMonth = $userPlan ? $marketPlaceService->countAnnouncementsInThisPeriod($user) : 0;
        $availableAnnouncements = 0;
        $availableUserPlan = null;

        if ($userPlan) {
            foreach ($userPlan->features as $planFeature => $value) {
                if ($planFeature === PlanFeature::ANNOUNCEMENT_CREATE) {
                    $availableAnnouncements = !$user->checkAnnouncementPlan($announcementInThisMonth, $marketplace->isActive()) ? 0 : ($value === -1 ? -1 : $value - $announcementInThisMonth);
                    $availableUserPlan = $userPlan;
                }
            }
        }

        return view("User::frontend.plan.choose-marketplace-plan", [
            'page'                    => [],
            'marketplace'             => $marketplace,
            'user'                    => $user,
            'plans'                   => Plan::query()
                ->where('plan_type', Plan::TYPE_ONE_TIME)
                ->where('role_id', $user->role->id)
                ->where('status', Plan::STATUS_PUBLISH)
                ->whereHas(Plan::RELATION_FEATURES, static function (Builder $builder) {
                    $builder->where('slug', PlanFeature::ANNOUNCEMENT_CREATE);
                })
                ->get(),
            'current_plan'            => $availableUserPlan,
            'available_announcements' => $availableAnnouncements,
            'isHasAvailablePlan' => Plan::query()
                ->where('plan_type', Plan::TYPE_RECURRING)
                ->where('role_id', $user->role->id)
            ->count() > 0,
            'userPlans' => $user->userPlans()
                ->where('status', UserPlan::NOT_USED)
                ->get()
                ->filter(static function (UserPlan $userPlan) {
                    return (int)($userPlan->features[PlanFeature::ANNOUNCEMENT_CREATE] ?? 0) > 0;
                })
        ]);

    }

    public function storePlan(Request $request, Marketplace $marketplace, MarketPlaceService $marketPlaceService): RedirectResponse
    {
        $this->checkPermission('marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE);
        /** @var User $user */
        $user = Auth::user();

        if ($marketplace->create_user !== $user->id && ($user->parent && $user->parent->id !== $marketplace->create_user)) {
            return abort(403);
        }

        if ($user->parent) {
            $user = $user->parent;
        }

        $data = $request->validate([
            'action' => 'required'
        ]);
        if ($data['action'] === Plan::TYPE_FREE) {
            $marketplace->publish(Marketplace::BASIC_EXPIRATION_DAYS);

            return redirect()->route('seller.all.marketplaces')->with('success', __('Your Announcement post has been posted'));
        } elseif ($data['action'] === Plan::TYPE_ONE_TIME) {
            CartManager::clear();
            /** @var Plan $plan */
            $plan = Plan::query()->findOrFail($request->input('plan_id'));
            CartManager::add(
                $plan, $plan->title, 1, $plan->price, ['action' => 'one_time', 'model' => Marketplace::class, 'model_id' => $marketplace->id]
            );

            return redirect()->route('checkout', ['redirectTo' => route('seller.all.marketplaces')]);
        } elseif ($data['action'] === 'user_plan') {
            if ($user->checkAnnouncementPlan($marketPlaceService->countAnnouncementsInThisPeriod($user), $marketplace->isActive())) {
                $user->load(User::RELATION_USER_PLAN);
                $marketplace->publish(false === $marketplace->isActive() ? $user->currentUserPlan->plan_data['expiration_announcement_time'] : null);

                return redirect()->route('seller.all.marketplaces')->with('success', __('Your Announcement post has been posted'));
            } else {
                return redirect()->back()->with('error', __('You don\'t have free posts'));
            }
        } elseif ($data['action'] === 'package') {
            /** @var UserPlan $userPlan */
            $userPlan = UserPlan::query()->findOrFail($request->input('plan_id'));
            if ($userPlan->hasFeature(PlanFeature::ANNOUNCEMENT_CREATE) && $userPlan->create_user === $user->id) {
                $userPlan->decrementFeature(PlanFeature::ANNOUNCEMENT_CREATE);
                $userPlan->save();
                $marketplace->publish(
                    $marketplace->expiration_date === null ? $userPlan->plan_data['expiration_announcement_time'] : null,
                    true
                );

                return redirect()->route('seller.all.marketplaces')->with('success', __('Your Announcement post has been posted'));
            } else {
                return redirect()->back()->with('error', __('You don\'t have free posts'));
            }
        }

        return back();
    }

    public function delete(Marketplace $Marketplace, Request $request)
    {
        $this->checkPermission('marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE);

        if (empty($Marketplace)) {
            return redirect(route('seller.all.marketplaces'));
        }

        $Marketplace->setAttribute('status', Marketplace::STATUS_DRAFT);
        $Marketplace->save();
        $Marketplace->delete();

        event(new UpdatedServiceEvent($Marketplace));

        return redirect()->back()->with('success', __('Deleted success!'));

    }

    public function bulk(BulkMarketplaceRequest $request)
    {
        $this->checkPermission('marketplace_manage');
        $data = $request->validated();

        switch ($request['action']) {
            case BulkMarketplaceRequest::DELETE:
                Marketplace::query()
                    ->when(is_candidate(), fn($query) => $query->where(function ($query) use ($data) {
                        $query->where('create_user', '=', Auth::id())
                            ->whereNull('company_id');
                    }))
                    ->when(is_employer(), fn($query) => $query->where(function ($query) use ($data) {
                        $query->where('company_id', '=', Auth::user()->company->id ?? '');
                    }))
                    ->whereIn("id", $data['ids'])
                    ->delete();
                break;
            case BulkMarketplaceRequest::DRAFT:
            case BulkMarketplaceRequest::PUBLISH:
                Marketplace::query()
                    ->when(is_candidate(), fn($query) => $query->where(function ($query) use ($data) {
                        $query->where('create_user', '=', Auth::id())
                            ->whereNull('company_id');
                    }))
                    ->when(is_employer(), fn($query) => $query->where(function ($query) use ($data) {
                        $query->where('company_id', '=', Auth::user()->company->id ?? '');
                    }))
                    ->whereIn("id", $data['ids'])
                    ->update(['status' => $data['action']]);
        }


        return response()->json(['status' => 'success', 'message' => __('Update success!')]);
    }

    public function MarketplaceExport()
    {
        $this->checkPermission('marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE);

        return (new MarketplaceExport())->download('Marketplace-' . date('M-d-Y') . '.xlsx');
    }
}
