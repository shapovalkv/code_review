<?php

namespace Modules\User\Controllers;

use App\Notifications\AdminChannelServices;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Matrix\Exception;
use Modules\Candidate\Models\Candidate;
use Modules\Candidate\Models\CandidateCategories;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Candidate\Models\CandidateSkills;
use Modules\Candidate\Models\Category;
use Modules\FrontendController;
use Modules\Job\Models\JobType;
use Modules\Location\Models\Location;
use Modules\Location\Services\LocationService;
use Modules\Skill\Models\Skill;
use Modules\User\Events\NewVendorRegistered;
use Modules\User\Events\SendMailUserRegistered;
use Modules\User\Events\UserSubscriberSubmit;
use Modules\User\Models\Subscriber;
use Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Modules\Vendor\Models\VendorRequest;
use Validator;
use Modules\Booking\Models\Booking;
use App\Helpers\ReCaptchaEngine;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Booking\Models\Enquiry;
use Illuminate\Support\Str;
use Modules\Company\Models\Company;

class UserController extends FrontendController
{
    use AuthenticatesUsers;

    protected $enquiryClass;
    protected $company;

    public function __construct()
    {
        $this->enquiryClass = Enquiry::class;
        $this->company = Company::class;
        parent::__construct();
    }

    public function dashboard(Request $request)
    {
        $this->checkPermission('dashboard_vendor_access');
        $user_id = Auth::id();
        $data = [
            'cards_report' => Booking::getTopCardsReportForVendor($user_id),
            'earning_chart_data' => Booking::getEarningChartDataForVendor(strtotime('monday this week'), time(), $user_id),
            'page_title' => __("Vendor Dashboard"),
            'breadcrumbs' => [
                [
                    'name' => __('Dashboard'),
                    'class' => 'active'
                ]
            ]
        ];
        return view('User::frontend.dashboard', $data);
    }

    public function reloadChart(Request $request)
    {
        $chart = $request->input('chart');
        $user_id = Auth::id();
        switch ($chart) {
            case "earning":
                $from = $request->input('from');
                $to = $request->input('to');
                return $this->sendSuccess([
                    'data' => Booking::getEarningChartDataForVendor(strtotime($from), strtotime($to), $user_id)
                ]);
                break;
        }
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        $data = [
            'row' => $user,
            'page_title' => __("Profile"),
            'is_user_page' => true,
            'locations' => Location::query()->where('status', 'publish')->get()->toTree(),
            'categories' => Category::get()->toTree(),
            'skills' => Skill::query()->where('status', 'publish')->where('skill_type', '=','candidate')->get(),
            'random_skills' => Skill::query()->where('status', 'publish')->where('skill_type', '=','candidate')->inRandomOrder()->limit(5)->get(),
            'job_types' => JobType::query()->where('status', 'publish')->get(),
            'cvs' => CandidateCvs::query()->where('origin_id', $user->id)->with('media')->get(),
            'menu_active' => 'user_profile'
        ];
        return view('User::frontend.profile', $data);
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
        ]);
//        $input = $request->except('bio');
        $user->fill($request->input());
        $user->bio = clean($request->input('bio'));
        $user->birthday = date("Y-m-d", strtotime($user->birthday));
        $user->save();
        return redirect()->back()->with('success', __('Update successfully'));
    }

    public function bookingHistory(Request $request)
    {
        $user_id = Auth::id();
        $data = [
            'bookings' => Booking::getBookingHistory($request->input('status'), $user_id),
            'statues' => config('booking.statuses'),
            'breadcrumbs' => [
                [
                    'name' => __('Booking History'),
                    'class' => 'active'
                ]
            ],
            'page_title' => __("Booking History"),
        ];
        return view('User::frontend.bookingHistory', $data);
    }

    public function userLogin(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $messages = [
            'email.required' => __('Email is required field'),
            'email.email' => __('Email invalidate'),
            'password.required' => __('Password is required field'),
        ];
        if (ReCaptchaEngine::isEnable() and setting_item("recaptcha_enable")) {
            $codeCapcha = $request->input('g-recaptcha-response');
            if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
                throw ValidationException::withMessages([
                    'recaptcha' => __('Please verify the captcha'),
                ]);
            }
        }
        $data = Validator::make($request->all(), $rules, $messages)->validated();

        if (Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ], $request->has('remember'))) {
            if (in_array(Auth::user()->status, ['blocked'])) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => __('Your account has been blocked'),
                ]);
            }
            if (!empty($request->job_id)){
                $redirectUrl = !empty(parse_url($request->headers->get('referer'), PHP_URL_QUERY)) ?
                    $request->headers->get('referer').'&job_id=' . $request->job_id."#apply" :
                    $request->headers->get('referer').'?job_id=' . $request->job_id."#apply";
            } else {
                $redirectUrl = $request->headers->get('referer');
            }
            return Inertia::location(
                    $request->input('redirect') ?? $redirectUrl ?? url(app_get_locale(false, '/'))
            );
        }
        throw ValidationException::withMessages([
            'email' => __('Email or password incorrect'),
        ]);
    }

    public function candidateUpdate(Request $request, LocationService $locationService)
    {
        if (!is_candidate() && !is_admin() && !is_employer()) {
            abort(403);
        }
        $row = \App\User::find(Auth::id());
        if (empty($row)) {
            abort(404);
        }
        if ($row->id != Auth::user()->id and !Auth::user()->hasPermission('user_manage')) {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'status' => 'required|max:50',
//                'phone' => 'required',
            'role_id' => 'sometimes|required|max:11',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($row->id)
            ],
            'map_location' => Rule::requiredIf(is_candidate())
        ], [
            'map_location.required' => __('Please select location from drop down or select place on the map'),
        ]);

        $row->name = $request->input('name');
        $row->first_name = $request->input('first_name');
        $row->last_name = $request->input('last_name');
        $row->phone = $request->input('phone');
        $row->birthday = $request->input('birthday') ? Carbon::createFromFormat(get_date_format(), $request->input('birthday')) : null;
        $row->bio = clean($request->input('bio'));
        $row->status = $request->input('status');
        $row->avatar_id = $request->input('avatar_id');
        $row->email = $request->input('email');
        $row->need_update_pw = $request->input('need_update_pw');

        if ($this->hasPermission('user_manage') && $role_id = $request->input('role_id')) {
            $row->role_id = $role_id;
        }


        if ($row->save()) {
            if ($row->role_id == 3) {
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
                    'expected_salary_min',
                    'expected_salary_max',
                    'salary_type',
                    'website',
                    'education_level',
                    'experience_year',
                    'languages',

                    'address',
                    'city',
                    'country',
                    'map_lat',
                    'map_lng',
                    'map_zoom',

                    'education',
                    'experience',
                    'award',
                    'social_media',
                    'video_cover_id',
                    'job_type_id'

                ], $request->input());
                $cData->location_id = $locationService->store($request);
                $cData->allow_search = $request->is_hidden_profile == 1 ? 'draft' : 'publish';
                $cData->seniority_level = implode(",", $request->seniority_level);

//                    $cData->save();
                $cData->createSeoAndSave([
                    'seo_title' => 'constructional_job_board_and_marketplace | '.  $cData->title . ' | ' . $row->name,
                    'seo_desc' => mb_strimwidth($row->bio, 0, 130, '...'),
                    'seo_keywords' => null //TODO Create auto generating seo keywords with SEO team requirements
                ]);

                $uploadedCandidate = CandidateCvs::query()->where('origin_id', $row->id)->pluck('file_id')->toArray();
                $cvUpload = $request->input('cvs', []);
                CandidateCvs::query()->where('origin_id', $row->id)->whereNotIn('file_id', $cvUpload)->delete();
                if (!empty($cvUpload)) {
                    foreach ($cvUpload as $oneCv) {
                        if (in_array($oneCv, $uploadedCandidate)) {
                            continue;
                        }
                        $cv = new CandidateCvs();
                        $cv->file_id = $oneCv;
                        $cv->origin_id = $row->id;
                        $cv->is_default = ($oneCv == @$request->csv_default) ? 1 : 0;
                        $cv->create_user = Auth::id();
                        $cv->save();
                    }
                }

                if (!empty($cvId = $request->csv_default)){
                    CandidateCvs::query()->where('create_user', $row->id)->where('is_default', 1)->update(['is_default' => 0]);
                    CandidateCvs::query()->where('file_id', $cvId)->update(['is_default' => isset($cvId)]);
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
                                'skill_id' => $skill
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
                                'cat_id' => $category
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
            if ($request->session()->has('job_id') && $request->session()->has('job_apply_refer')) {
                $jobId = $request->session()->get('job_id');
                $jobRefer = $request->session()->get('job_apply_refer');

                $redirectUrl = !empty(parse_url($jobRefer, PHP_URL_QUERY)) ?
                    $jobRefer.'&job_id=' . $jobId."#apply" :
                    $jobRefer.'?job_id=' . $jobId."#apply";
                return redirect($redirectUrl);
            }
            $request->session()->forget(['complete_registration']);

            return back()->with('success', __('Your profile has been successfully updated.'));

        }
    }

    /**
     * @throws ValidationException
     */
    public function userRegister(Request $request)
    {
        $rules = [
            'first_name' => [
                'required',
                'string',
                'max:255'
            ],
            'last_name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users'
            ],
            'password' => [
                'required',
                'string'
            ],
//            'phone'       => ['required','unique:users'],
//            'term'       => ['required'],
        ];
        $messages = [
            // 'phone.required'      => __('Phone is required field'),
            'email.required' => __('Email is required field'),
            'email.email' => __('Email invalidate'),
            'password.required' => __('Password is required field'),
            'first_name.required' => __('The first name is required field'),
            'last_name.required' => __('The last name is required field'),
            //'term.required'       => __('The terms and conditions field is required'),
        ];
        if (ReCaptchaEngine::isEnable() and setting_item("recaptcha_enable")) {
            $codeCapcha = $request->input('g-recaptcha-response');
            if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
                $errors = new MessageBag(['recaptcha' => __('Please verify the captcha')]);
                return response()->json([
                    'error' => true,
                    'messages' => $errors
                ], 200);
            }
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw ValidationException::withMessages([
                $validator->errors()
            ]);
        } else {

            $user = \App\User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'status' => $request->input('publish', 'publish'),
                // 'phone'    => $request->input('phone'),
            ]);
            event(new Registered($user));
            Auth::loginUsingId($user->id);
            try {
                event(new SendMailUserRegistered($user));
            } catch (Exception $exception) {

                Log::warning("SendMailUserRegistered: " . $exception->getMessage());
            }
            $role = $request->input('account_type');
            if (in_array($role, ['employer', 'employee'])) {
                $user->assignRole($role);
                if ($role == 'employee') {
                    Candidate::query()->insert(['id' => $user->id]);
                    $request->session()->put('complete_registration', 'Please complete your profile to finish the registration.');
                }
                if ($role == 'employer' ) {
                    $request->session()->put('complete_registration', 'Please complete your company profile to finish the registration.');
                }
            }

            if (!empty($request->job_id)){
                $request->session()->put('job_id', $request->job_id);
                $request->session()->put('job_apply_refer', $request->headers->get('referer'));
            }

            return Inertia::location($role == 'employee' ? route('user.profile.index') : route('user.company.profile'));
        }
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255'
        ]);
        $check = Subscriber::withTrashed()->where('email', $request->input('email'))->first();
        if ($check) {
            if ($check->trashed()) {
                $check->restore();
                return $this->sendSuccess([], __('Thank you for subscribing'));
            }
            return $this->sendError(__('You are already subscribed'));
        } else {
            $a = new Subscriber();
            $a->email = $request->input('email');
            $a->first_name = $request->input('first_name');
            $a->last_name = $request->input('last_name');
            $a->save();

            event(new UserSubscriberSubmit($a));

            return $this->sendSuccess([], __('Thank you for subscribing'));
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect(app_get_locale(false, '/'));
    }

}
