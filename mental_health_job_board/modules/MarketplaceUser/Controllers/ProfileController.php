<?php

namespace Modules\MarketplaceUser\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\MarketplaceUser\Models\MarketplaceUser;
use Modules\FrontendController;

class ProfileController extends FrontendController
{
    /**
     * @var MarketplaceUser
     */
    private $marketplace_user;

    public function __construct(MarketplaceUser $marketplace_user)
    {
        parent::__construct();
        $this->marketplace_user = $marketplace_user;
    }

    public function index()
    {
        if (!is_marketplace_user()) abort(403);

        $user = auth()->user();

        $data = [
            'row' => $user->marketplace_user ?? $this->marketplace_user,
            'page_title' => __("MarketplaceUser Profile"),
            'user' => $user,
            'is_user_page' => true,
            'menu_active' => 'marketplace_user_profile',
            'languages' => config('languages.locales')
        ];
        return view('MarketplaceUser::frontend.profile.edit', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255'
        ]);

        $validator->validate();

        $user = auth()->user();

        $marketplace_user = $user->marketplace_user ?? $this->marketplace_user;

        $marketplace_user->id = $user->id;


        $user->fillByAttr([
            'first_name',
            'last_name',
            'avatar_id',
            'phone'
        ], $request->input());
        $user->save();

        $marketplace_user->save();

        return back()->with('success', __("MarketplaceUser profile saved"));
    }
}
