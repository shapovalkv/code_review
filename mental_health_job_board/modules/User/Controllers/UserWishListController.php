<?php
namespace Modules\User\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\FrontendController;
use Modules\User\Models\UserWishList;
use Illuminate\Http\Request;

class UserWishListController extends FrontendController
{
    protected $userWishListClass;
    public function __construct()
    {
        parent::__construct();
        $this->userWishListClass = UserWishList::class;
    }

    public function index(Request $request): View
    {

        $wishlist = UserWishList::query();

        if(is_employer()){
            if($s = $request->get('s')) {
                $wishlist = $wishlist->whereHas('candidate', function ($q) use ($s) {
                    $q->where('title', 'like', '%' . $s . '%');
                });
            }
            $wishlist = $wishlist->where('object_model', 'candidate');
        }else {
            if($s = $request->get('s')) {
                $wishlist = $wishlist->whereHas('job', function ($q) use ($s) {
                    $q->where('title', 'like', '%' . $s . '%');
                });
            }
            $wishlist = $wishlist->where('object_model', 'job');
        }
        $wishlist = $wishlist->where("user_wishlist.user_id",Auth::id())
            ->orderBy('user_wishlist.id', 'desc');
        $data = [
            'rows' => $wishlist->paginate(10),
            'page_title'         => is_employer() ? __("Bookmark Candidates") : __("Bookmark Jobs"),
            'menu_active' => is_employer() ? 'user_bookmark_employer' : 'user_bookmark',
        ];
        return view('User::frontend.wishList.index', $data);
    }

    public function followingEmployers(Request $request){
        abort(403);
        $wishlist = $this->userWishListClass::query();
        if($s = $request->get('s')){
            $wishlist = $wishlist->whereHas('company', function ($q) use ($s) {
                $q->where('name', 'like', '%'.$s.'%');
            });
        }
        $wishlist = $wishlist->where('object_model', 'company')
            ->where("user_wishlist.user_id",Auth::id())
            ->orderBy('user_wishlist.id', 'desc');
        $data = [
            'rows' => $wishlist->paginate(10),
            'page_title'         => __("Following Employers"),
            'menu_active' => 'following_employers',
        ];
        return view('User::frontend.wishList.following-employers', $data);

    }

    public function handleWishList(Request $request){
        $objectId = $request->input('object_id');
        $objectModel = $request->input('object_model');
        if (empty($objectId)) {
            return $this->sendError(__("Object ID is required"));
        }
        if (empty($objectModel)) {
            return $this->sendError(__("Object model is required"));
        }

        $model = UserWishList::query()
            ->where('object_id', $objectId)
            ->where('object_model', $objectModel)
            ->where('user_id', Auth::id())
            ->first();

        if (!empty($model)) {
            $model->delete();

            return $this->sendSuccess(['class' => '', 'fragments' => $this->fragments()]);
        }

        $model = new UserWishList($request->input());
        $model->user_id = Auth::id();
        $model->save();

        return $this->sendSuccess(['class' => "active", 'fragments' => $this->fragments()]);
    }

    public function fragments(){
        return [
            '.wishlist_count'=>auth()->user()->wishlist_count ?? 0
        ];
    }

    public function remove(Request $request): JsonResponse
    {
        $meta = UserWishList::query()
            ->where('object_id', $request->input('id'))
            ->where('user_id',Auth::id())
            ->first();

        if(!empty($meta)){
            $meta->delete();
            return $this->sendSuccess(['message' => __('Delete success!'), 'fragments'=>$this->fragments()]);
        }

        return $this->sendError(['message' => __('Delete fail!')]);
    }
}
