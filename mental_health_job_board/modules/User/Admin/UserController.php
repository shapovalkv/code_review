<?php

namespace Modules\User\Admin;

use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\AdminController;
use Modules\Candidate\Models\Candidate;
use Modules\Candidate\Models\CandidateCategories;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Candidate\Models\CandidateSkills;
use Modules\Candidate\Models\Category;
use Modules\Location\Models\Location;
use Modules\Skill\Models\Skill;
use Modules\User\Events\VendorApproved;
use Modules\User\Exports\UserExport;
use Modules\User\Models\Plan;
use Modules\User\Models\Role;
use Modules\Company\Models\CompanyRequest;
use Modules\User\Requests\GetUserListRequest;
use Modules\User\Services\UserService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Modules\User\Models\UserPlan;
use Modules\User\Requests\ApplyUserPlanRequest;

class UserController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu('admin/module/user');
        if (!is_admin()) {
            $this->middleware('verified');
        }
        parent::__construct();
    }

    public function index(GetUserListRequest $request, UserService $userService): View
    {
        $this->checkPermission('user_manage');

        $data = [
            'rows'  => $userService->getUserBuilder(
                $request->query('s'),
                $request->query('role'),
                $request->query('orderBy'),
                $request->query('orderDirection')
            )->paginate(20),
            'roles' => Role::all()
        ];
        return view('User::admin.index', $data);
    }

    public function create(Request $request)
    {
        $row = new \Modules\User\Models\User();
        $data = [
            'row'                => $row,
            'roles'              => Role::all(),
            'candidate_create'   => $request->get('candidate_create', 0),
            'candidate'          => new Candidate(),
            'languages'          => config('languages.locales'),
            'candidate_location' => Location::where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'categories'         => Category::get()->toTree(),
            'skills'             => Skill::query()->where('status', 'publish')->get(),
            'breadcrumbs'        => [
                [
                    'name' => __("Users"),
                    'url'  => 'admin/module/user'
                ]
            ]
        ];
        return view('User::admin.detail', $data);
    }

    public function edit(Request $request, $id)
    {
        $row = User::find($id);
        if (empty($row)) {
            return redirect('admin/module/user');
        }
        if ($row->id != Auth::user()->id and !Auth::user()->hasPermission('user_manage')) {
            abort(403);
        }
        $data = [
            'row'                => $row,
            'roles'              => Role::all(),
            'candidate_location' => Location::where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'categories'         => Category::get()->toTree(),
            'cvs'                => CandidateCvs::query()->where('origin_id', $id)->with('media')->get(),
            'skills'             => Skill::query()->where('status', 'publish')->get(),
            'breadcrumbs'        => [
                [
                    'name' => __("Users"),
                    'url'  => 'admin/module/user'
                ],
                [
                    'name'  => __("Edit User: #:id", ['id' => $row->id]),
                    'class' => 'active'
                ],
            ],
            'languages' => config('languages.locales'),
            'plans'     => Plan::query()->where('status', Plan::STATUS_PUBLISH)->get(),
        ];
        return view('User::admin.detail', $data);
    }

    public function password(Request $request, $id)
    {

        $row = User::find($id);
        $data = [
            'row'         => $row,
            'currentUser' => Auth::user()
        ];
        if (empty($row)) {
            return redirect('admin/module/user');
        }
        if ($row->id != Auth::user()->id and !Auth::user()->hasPermission('user_manage')) {
            abort(403);
        }
        return view('User::admin.password', $data);
    }

    public function changepass(Request $request, $id)
    {
        if (is_demo_mode()) {
            return redirect()->back()->with("error", __("DEMO MODE: You can not change password!"));
        }
        $rules = [];
        $urow = User::find($id);
        if ($urow->id != Auth::user()->id and !Auth::user()->hasPermission('user_manage')) {
            abort(403);
        }
        $request->validate([
            'password'              => 'required|min:6|max:255',
            'password_confirmation' => 'required',
        ]);
        $password_confirmation = $request->input('password_confirmation');
        $password = $request->input('password');
        if ($password != $password_confirmation) {
            return redirect()->back()->with("error", __("Your New password does not matches. Please type again!"));
        }
        if ($urow->id != Auth::user()->id and !Auth::user()->hasPermission('user_manage')) {
            if ($password) {
                if ($urow->id != Auth::user()->id) {
                    $rules['old_password'] = 'required';
                }
                $rules['password'] = 'required|string|min:6|confirmed';
            }
            $this->validate($request, $rules);
            if ($password) {
                if (!(Hash::check($request->input('old_password'), $urow->password))) {
                    // The Old passwords matches
                    return redirect()->back()->with("error", __("Your current password does not matches with the password you provided. Please try again."));
                }
            }
        }
        $urow->password = bcrypt($password);
        if ($urow->save()) {

            if ($request->input('role_id') and $role = Role::findById($request->input('role_id'))) {
                $urow->assignRole($role);
            }
            return redirect()->back()->with('success', __('Password updated!'));
        }
    }

    public function store(Request $request, $id)
    {
        if (!is_candidate() && !is_admin() && !is_employer()) {
            abort(403);
        }
        if ($id and $id > 0) {
            $row = User::find($id);
            if (empty($row)) {
                abort(404);
            }
            if ($row->id != Auth::user()->id and !Auth::user()->hasPermission('user_manage')) {
                abort(403);
            }

            $request->validate([
                'first_name' => 'required|max:255',
                'last_name'  => 'required|max:255',
                'status'     => 'required|max:50',
                'phone'      => 'required',
                'role_id'    => 'sometimes|required|max:11',
                'email'      => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($row->id)
                ],
            ]);

        } else {
            $check = Validator::make($request->input(), [
                'first_name' => 'required|max:255',
                'last_name'  => 'required|max:255',
                'status'     => 'required|max:50',
                'phone'      => 'required',
                'role_id'    => 'sometimes|required|max:11',
                'email'      => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users')
                ],
            ]);

            if (!$check->validated()) {
                return back()->withInput($request->input());
            }

            $row = new User();
            $row->email = $request->input('email');
        }

        $row->name = $request->input('name');
        $row->first_name = $request->input('first_name');
        $row->last_name = $request->input('last_name');
        $row->phone = $request->input('phone');
        $row->birthday = date("Y-m-d", strtotime($request->input('birthday')));
        $row->bio = clean($request->input('bio'));
        $row->status = $request->input('status');
        $row->avatar_id = $request->input('avatar_id');
        $row->email = $request->input('email');
        $row->need_update_pw = $request->input('need_update_pw');
        $row->show_tutorial_popup = 1;

        if ($this->hasPermission('user_manage') && $role_id = $request->input('role_id')) {
            $row->role_id = $role_id;
        }


        if ($row->save()) {
            if ($row->role_id == 3) {

                $check = Validator::make($request->input(), [
                    'title' => 'required|max:255'
                ]);

                if (!$check->validated()) {
                    return back()->withInput($request->input());
                }

                $cData = Candidate::find($row->id);
                if (empty($cData)) {
                    DB::table('bc_candidates')->insert([
                        'id' => $row->id
                    ]);
                    $cData = Candidate::find($row->id);
                }
                $cData->fillByAttr([
                    'title',
                    'gallery',
                    'video',
                    'gender',
                    'expected_salary',
                    'salary_type',
                    'website',
                    'education_level',
                    'experience_year',
                    'languages',
                    'allow_search',

                    'address',
                    'city',
                    'country',
                    'location_id',
                    'map_lat',
                    'map_lng',
                    'map_zoom',

                    'education',
                    'experience',
                    'award',
                    'social_media',
                    'video_cover_id'

                ], $request->input());
//                    $cData->save();
                if (!empty($request->input('languages'))) {
                    $cData->languages = implode(',', $request->input('languages'));
                } else {
                    $cData->languages = '';
                }
                $cData->saveOriginOrTranslation($request->query('lang'), true);


                $uploadedCandidate = CandidateCvs::query()->where('origin_id', $row->id)->pluck('file_id')->toArray();
                $cvUpload = $request->input('cvs', []);
                if (!empty($cvUpload)) {
                    CandidateCvs::query()->where('origin_id', $row->id)->whereNotIn('file_id', $cvUpload)->delete();
                    foreach ($cvUpload as $oneCv) {
                        if (in_array($oneCv, $uploadedCandidate)) {
                            continue;
                        }
                        $cv = new CandidateCvs();
                        $cv->file_id = $oneCv;
                        $cv->origin_id = $row->id;
                        $cv->is_default = 0;
                        $cv->create_user = Auth::id();
                        $cv->save();
                    }

                    //Update Default CV
                    CandidateCvs::query()->where("origin_id", $row->id)->update(['is_default' => 0]);
                    CandidateCvs::query()->where("origin_id", $row->id)->where('file_id', @$request->csv_default)->update(['is_default' => 1]);
                }

                if (!empty($request->skills)) {
                    $cSkills = CandidateSkills::query()->where('origin_id', $row->id)->pluck('skill_id')->toArray();
                    foreach ($request->skills as $skill) {
                        $pos = array_search(intval($skill), $cSkills);
                        if ($pos !== false) {
                            unset($cSkills[$pos]);
                        } else {
                            DB::table('bc_candidate_skills')->insert([
                                'origin_id' => $row->id,
                                'skill_id'  => $skill
                            ]);
                        }
                    }
                    if (!empty($cSkills)) {
                        CandidateSkills::query()->where('origin_id', $row->id)->whereIn('skill_id', $cSkills)->delete();
                    }
                } else {
                    CandidateSkills::query()->where('origin_id', $row->id)->delete();
                }

                if (!empty($request->categories)) {
                    $cCats = CandidateCategories::query()->where('origin_id', $row->id)->pluck('cat_id')->toArray();
                    foreach ($request->categories as $category) {
                        $pos = array_search(intval($category), $cCats);
                        if ($pos !== false) {
                            unset($cCats[$pos]);
                        } else {
                            DB::table('bc_candidate_categories')->insert([
                                'origin_id' => $row->id,
                                'cat_id'    => $category
                            ]);
                        }
                    }
                    if (!empty($cCats)) {
                        CandidateCategories::query()->where('origin_id', $row->id)->whereIn('cat_id', $cCats)->delete();
                    }
                } else {
                    CandidateCategories::query()->where('origin_id', $row->id)->delete();
                }

            }

            if ($id > 0) {
                return back()->with('success', __('User updated'));
            } else {
                return redirect(route('user.admin.detail', $row->id))->with('success', __('User created'));
            }
        }
    }

    public function getForSelect2(Request $request)
    {
        $q = $request->query('q');
        $query = User::select('*');
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('first_name', 'like', '%' . $q . '%')->orWhere('last_name', 'like', '%' . $q . '%')->orWhere('email', 'like', '%' . $q . '%')->orWhere('id', $q)->orWhere('phone', 'like', '%' . $q . '%');
            });
        }
        $res = $query->orderBy('id', 'desc')->orderBy('first_name', 'asc')->limit(20)->get();
        $data = [];
        if (!empty($res)) {
            if ($request->query("user_type") == "candidate") {
                //for only vendor
                foreach ($res as $item) {
                    if ($item->hasPermission("gig_manage")) {
                        $data[] = [
                            'id'   => $item->id,
                            'text' => $item->getDisplayName() ? $item->getDisplayName() . ' (#' . $item->id . ')' : $item->email . ' (#' . $item->id . ')',
                        ];
                    }
                }
            } else {
                //for all
                foreach ($res as $item) {
                    $data[] = [
                        'id'   => $item->id,
                        'text' => $item->getDisplayName() ? $item->getDisplayName() . ' (#' . $item->id . ')' : $item->email . ' (#' . $item->id . ')',
                    ];
                }
            }
        }
        return response()->json([
            'results' => $data
        ]);
    }

    public function bulkEdit(Request $request)
    {
        if (is_demo_mode()) {
            return redirect()->back()->with("error", "DEMO MODE: You are not allowed to do it");
        }
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids))
            return redirect()->back()->with('error', __('Select at least 1 item!'));
        if (empty($action))
            return redirect()->back()->with('error', __('Select an Action!'));
        if ($action == 'delete') {
            foreach ($ids as $id) {
                if ($id == Auth::id()) continue;
                $query = User::where("id", $id)->first();
                $candidate = Candidate::where("id", $id)->first();
                if (!empty($query)) {
                    $query->email .= '_d_' . uniqid() . rand(0, 99999);
                    $query->save();
                    $query->delete();

                    if (!empty($candidate)) {
                        $candidate->appliedJobs()->delete();
                        $candidate->delete();
                    }
                }
            }
        } else {
            foreach ($ids as $id) {
                User::where("id", $id)->update(['status' => $action]);
            }
        }
        return redirect()->back()->with('success', __('Updated successfully!'));
    }

    public function userUpgradeRequest(Request $request)
    {
        $this->checkPermission('user_manage');
        $listUser = CompanyRequest::query();
        $data = [
            'rows'  => $listUser->with(['user', 'role', 'approvedBy'])->orderBy('id', 'desc')->paginate(20),
            'roles' => Role::all(),

        ];
        return view('User::admin.upgrade-user', $data);
    }

    public function userUpgradeRequestApproved(Request $request)
    {
        $this->checkPermission('user_manage');
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids))
            return redirect()->back()->with('error', __('Select at leas 1 item!'));
        if (empty($action))
            return redirect()->back()->with('error', __('Select an Action!'));

        switch ($action) {
            case "delete":
                foreach ($ids as $id) {
                    $query = CompanyRequest::find($id);
                    if (!empty($query)) {
                        $query->delete();
                    }
                }
                return redirect()->back()->with('success', __('Deleted success!'));
                break;
            default:

                foreach ($ids as $id) {
                    $vendorRequest = CompanyRequest::find($id);
                    if (!empty($vendorRequest)) {
                        $vendorRequest->update(['status' => $action, 'approved_time' => now(), 'approved_by' => Auth::id()]);
                        $user = User::find($vendorRequest->user_id);
                        if (!empty($user)) {
                            $user->assignRole($vendorRequest->role_request);
                        }
                        event(new VendorApproved($user, $vendorRequest));
                    }
                }
                return redirect()->back()->with('success', __('Updated successfully!'));
                break;
        }
    }

    public function userUpgradeRequestApprovedId(Request $request, $id)
    {
        $this->checkPermission('user_manage');
        if (empty($id))
            return redirect()->back()->with('error', __('Select at least 1 item!'));

        $vendorRequest = CompanyRequest::find($id);
        if (!empty($vendorRequest)) {
            $vendorRequest->update(['status' => 'approved', 'approved_time' => now(), 'approved_by' => Auth::id()]);
            $user = User::find($vendorRequest->user_id);
            if (!empty($user)) {
                $user->assignRole($vendorRequest->role_request);
            }

            event(new VendorApproved($user, $vendorRequest));
        }
        return redirect()->back()->with('success', __('Updated successfully!'));
    }

    public function export(GetUserListRequest $request, UserService $userService): BinaryFileResponse
    {
        return (new UserExport(
            $userService->getUserBuilder(
                $request->query('s'),
                $request->query('role'),
                $request->query('orderBy'),
                $request->query('orderDirection')
            )
        ))->download('user-' . date('M-d-Y') . '.xlsx');
    }

    public function verifyEmail(Request $request, $id)
    {
        $user = User::find($id);
        if (!empty($user)) {
            $user->email_verified_at = now();
            $user->save();
            return redirect()->back()->with('success', __('Verify email successfully!'));
        } else {
            return redirect()->back()->with('error', __('Verify email cancel!'));
        }
    }

    public function loginAs(User $user): RedirectResponse
    {
        Auth::login($user);

        return redirect('/');
    }

    public function getForSelect2ByRole(Request $request)
    {
        $pre_selected = $request->query('pre_selected');
        $selected = $request->query('selected');

        if ($pre_selected && $selected) {
            if (is_array($selected)) {
                $imploded_strings = implode("','", $selected);

                $query = \Modules\User\Models\User::query()->select('id', DB::raw('name as text'));
                $items = $query->whereIn('users.id', $selected)->take(50)->orderByRaw(DB::raw("FIELD(id, '$imploded_strings')"))->get();
                return response()->json([
                    'items' => $items
                ]);
            }
            $item = User::find($selected);
            if (empty($item)) {
                return response()->json([
                    'text' => ''
                ]);
            } else {
                return response()->json([
                    'text' => $item->name
                ]);
            }
        }
        $q = $request->query('q');
        $query = User::query()
            ->select('id', 'name as text')
            ->whereIn('role_id', $request->input('ids'));
        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }
        $res = $query->orderBy('id', 'desc')->limit(20)->get();
        return response()->json([
            'results' => $res
        ]);
    }

    public function applyUserPlan(User $user, ApplyUserPlanRequest $request): JsonResponse
    {
        $plan = Plan::query()->findOrFail($request->post('plan_id'));

        $newPlan = $user->applyPackageByAdmin($plan);

        return response()->json([
            'success' => true,
            'plan' => $newPlan->plan_data['title']
        ]);
    }

    public function cancelUserPlan(UserPlan $userPlan): JsonResponse
    {
        $userPlan->setAttribute('features', []);
        $userPlan->status = UserPlan::USED;

        return response()->json([
            'success' => $userPlan->save(),
        ]);
    }

}
