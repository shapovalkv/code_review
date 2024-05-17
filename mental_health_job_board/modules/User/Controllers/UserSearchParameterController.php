<?php

namespace Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Candidate\Models\Category;
use Modules\Job\Models\JobCategory;
use Modules\Job\Models\JobType;
use Modules\Location\Models\Location;
use Modules\Marketplace\Models\MarketplaceCategory;
use Modules\User\Models\UserPageSearchParameters;
use Modules\User\Requests\StoreUserSearchParameterRequest;

class UserSearchParameterController extends Controller
{

    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();
        $searches = UserPageSearchParameters::query()->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate();

        $locIds = array_filter(array_map(static function (UserPageSearchParameters $s) {
            return $s->parameters['location'] ?? null;
        }, $searches->items()));

        $data = [
            'rows'                      => $searches,
            'page_title'                => is_employer() || is_employee() ? __("Candidates Saved Search Parameters") : __("Jobs Saved Search Parameters"),
            'menu_active'               => 'user_saved_search',
            'jobTypes'                  => JobType::query()->get(),
            'listLocations'             => Location::query()->whereIn('id', $locIds)->get()->keyBy('id'),
            'candidateListCategories'   => Category::query()->get()->keyBy('id'),
            'jobListCategories'         => JobCategory::query()->get()->keyBy('id'),
            'marketplaceListCategories' => MarketplaceCategory::query()->get()->keyBy('slug'),
        ];

        return view('User::frontend.savedSearch.index', $data);
    }

    public function store(StoreUserSearchParameterRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = [
            'name'       => $request->input('name') ?? ($request->input('page') . ' page save search'),
            'user_id'    => $user->id,
            'page'       => $request->input('page'),
            'parameters' => $request->input('data'),
        ];

        if ($request->input('id')) {
            $id = UserPageSearchParameters::query()->where('id', $request->input('id'))->update($data);
        } else {
            /** @var UserPageSearchParameters $save */
            $save = UserPageSearchParameters::query()->create($data);
            $id = $save->id;
        }

        if ($id) {
            return response()->json([
                'error'    => false,
                'messages' => __('Your search parameters are ' . ($request->input('id') ? 'updated' : 'saved') . ' successfully'),
                'redirect' => false,
                'data'     => [
                    'id' => $id,
                ]
            ]);
        }

        return response()->json([
            'error'    => true,
            'messages' => __('Error'),
            'redirect' => false
        ], 400);
    }

    public function update(Request $request, UserPageSearchParameters $pageSearchParameters): JsonResponse
    {
        if ($pageSearchParameters->update(['name' => $request->input('name')])) {
            return response()->json([
                'error'    => false,
                'messages' => __('Your search parameters name are updated successfully'),
                'redirect' => false
            ]);
        }
        return response()->json([
            'error'    => true,
            'messages' => __('Error'),
            'redirect' => false
        ], 400);
    }

    public function delete(UserPageSearchParameters $pageSearchParameters): JsonResponse
    {
        if ($pageSearchParameters->delete()) {
            return response()->json([
                'error'    => false,
                'messages' => __('Your search parameters are deleted successfully'),
                'redirect' => false
            ]);
        }

        return response()->json([
            'error'    => true,
            'messages' => __('Error'),
            'redirect' => false
        ], 400);
    }
}
