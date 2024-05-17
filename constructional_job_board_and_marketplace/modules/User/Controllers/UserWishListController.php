<?php

namespace Modules\User\Controllers;

use App\Services\BasicFilterService;
use Illuminate\Support\Facades\Auth;
use Modules\FrontendController;
use Modules\Job\Models\Job;
use Modules\User\Models\UserWishList;
use Illuminate\Http\Request;
use Modules\User\Resources\WishList\CandidateListResource;
use Modules\User\Resources\WishList\CompanyListResource;
use Modules\User\Resources\WishList\EquipmentListResource;
use Modules\User\Resources\WishList\JobListResource;

class UserWishListController extends FrontendController
{
    protected $userWishListClass;

    public function __construct()
    {
        parent::__construct();
        $this->userWishListClass = UserWishList::class;
    }

    public function index(Request $request, BasicFilterService $basicFilterService)
    {
        $wishlist = UserWishList::search($request, Auth::user()->id);

        switch ($request->active) {
            case 'candidate':
                $rows = CandidateListResource::collection($wishlist);
                break;
            case 'job':
                $rows = JobListResource::collection($wishlist);
                break;
            case 'equipment':
                $rows = EquipmentListResource::collection($wishlist);
                break;
            case 'company':
                $rows = CompanyListResource::collection($wishlist);
                break;
            default:
                if ($wishlist->isEmpty()) {
                    return redirect()->route('user.wishList.index', array_merge($request->query(), ['active' => 'candidate']));
                } else {
                    $row = $wishlist->items()[0];
                    return redirect()->route('user.wishList.index', array_merge($request->query(), ['active' => $row->object_model]));
                }
        }

        $data = [
            'rows' => $rows ?? null,
            'bookmark_counter' => $basicFilterService->activeWishlist($request->active, Auth::id()),
            'pagination' => [
                'pagination' => [
                    'total' => isset($rows) ? $rows->total() : null,
                    'last_page' => isset($rows) ? $rows->lastPage() : null,
                    'per_page' => isset($rows) ? $rows->perPage() : null,
                    'current_page' => isset($rows) ? $rows->currentPage() : null,
                ]
            ],
            'job_list' => is_employer() && (!isset($request->active) || $request->active == "candidate") ? Job::query()
                ->when(!is_null(Auth::user()->company), function($query){
                    $query->where('company_id', Auth::user()->company->id);
                })
                ->get()->map(function ($jobs) {
                    return $jobs->only(['id', 'title']);
                }) : null,
            'filters' => [
                'keywords' => $basicFilterService->popularSearchKeywords($request->keywords),
                'orderby' => $basicFilterService->orderby($request->orderby),
                'limit' => $basicFilterService->limit($request->limit),
                'active' => $basicFilterService->wishListTab($request->active),
            ],
            'page_title' => is_employer() ? __("Bookmarks Candidates") : __("Bookmarks Jobs"),
            'menu_active' => is_employer() ? 'user_bookmark_employer' : 'user_bookmark',
        ];

        if ($request->ajax()) {
            return response()->json($data);
        }

        return view('User::frontend.wishList.index', $data);
    }

    public function followingEmployers(Request $request)
    {
        $wishlist = $this->userWishListClass::query();
        if ($s = $request->get('s')) {
            $wishlist = $wishlist->whereHas('company', function ($q) use ($s) {
                $q->where('name', 'like', '%' . $s . '%');
            });
        }
        $wishlist = $wishlist->where('object_model', 'company')
            ->where("user_wishlist.user_id", Auth::id())
            ->orderBy('user_wishlist.id', 'desc');
        $data = [
            'rows' => $wishlist->paginate(10),
            'page_title' => __("Following Employers"),
            'menu_active' => 'following_employers',
        ];
        return view('User::frontend.wishList.following-employers', $data);

    }

    public function handleWishList(Request $request)
    {
        $object_id = $request->input('object_id');
        $object_model = $request->input('object_model');
        if (empty($object_id)) {
            return $this->sendError(__("Service ID is required"));
        }
        if (empty($object_model)) {
            return $this->sendError(__("Service type is required"));
        }

        $meta = $this->userWishListClass::where("object_id", $object_id)
            ->where("object_model", $object_model)
            ->where("user_id", Auth::id())
            ->first();
        if (!empty($meta)) {
            $meta->delete();
            return $this->sendSuccess(['class' => "", 'fragments' => $this->fragments()]);
        }
        $meta = new $this->userWishListClass($request->input());
        $meta->user_id = Auth::id();
        $meta->save();
        return $this->sendSuccess(['class' => "active", 'fragments' => $this->fragments()]);
    }

    public function fragments()
    {
        return [
            '.wishlist_count' => auth()->user()->wishlist_count ?? 0
        ];
    }

    public function remove(Request $request)
    {
        $meta = $this->userWishListClass::where("id", $request->input('id'))
            ->where("user_id", Auth::id())
            ->first();
        if (!empty($meta)) {
            $meta->delete();
            return $this->sendSuccess(['message' => __('Delete success!'), 'fragments' => $this->fragments()]);
        }
        return $this->sendError(['message' => __('Delete fail!')]);
    }
}
