<?php
namespace Modules\MarketplaceUser\Controllers;

use App\Helpers\ReCaptchaEngine;
use App\Notifications\PrivateChannelServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Matrix\Exception;
use Modules\FrontendController;
use Modules\MarketplaceUser\Models\MarketplaceUser;
use Modules\User\Models\User;

class MarketplaceUserController extends FrontendController
{


    /**
     * @var MarketplaceUser
     */
    private $marketplace_user;
    private $defaultListCountSearch = 40;

    public function __construct(MarketplaceUser $marketplace_user)
    {
        parent::__construct();
        $this->marketplace_user = $marketplace_user;
    }

    public function index(Request $request)
    {
        if((setting_item('marketplace_user_public_policy') == "employer" || setting_item('marketplace_user_public_policy') == "employer_applied") && !is_employer()) {
            return redirect('/');
        }

        $list = call_user_func([$this->marketplace_user,'search'],$request, $this->defaultListCountSearch);

        $markers = [];
        if (!empty($list)) {
            foreach ($list as $row) {
                $markers[] = [
                    "id"      => $row->id,
                    "title"   => $row->title,
                    "lat"     => (float)$row->map_lat,
                    "lng"     => (float)$row->map_lng,
                    'customMarker' => view('MarketplaceUser::frontend.layouts.details.marketplace_user-marker-avatar', ['row' => $row,'disable_lazyload'=>1])->render(),
                ];
            }
        }

        $limit_location = 10;
        $data = [
            'active_search_params' => $request->all(),
            'rows'               => $list,
            'list_search' => $this->defaultListCountSearch
        ];


        $layout = setting_item('marketplace_user_list_layout', 'v1');
        $style = "marketplace_user-list-$layout";
        $demo_layout = $request->get('_layout');
        if(!empty($demo_layout)){
            $demo_style = "marketplace_user-list-$demo_layout";
            $style = (View::exists("$this->marketplace_user::frontend.layouts.search.$demo_style")) ? $demo_style : "marketplace_user-list-$layout";
        }
        $data['style'] = $style;
        $data['layout'] = $demo_layout ? $demo_layout : 'v1';
        if ($data['layout'] == 'v5') $data['footer_null'] = true;

        return view('MarketplaceUser::frontend.index', $data);
    }

    public function detail(Request $request, MarketplaceUser $marketplace_user)
    {
        if((setting_item('marketplace_user_public_policy') == "employer" || setting_item('marketplace_user_public_policy') == "employer_applied")
            && !is_employer()
            && !Auth::user()->marketplace_user()->where('id', $marketplace_user->id)->exists()){
                abort(404);
        }

        if(setting_item('marketplace_user_public_policy') == "employer_applied") {

            $employer = User::with("company")->where("id", Auth::id())->first();
            if(empty($employer->company->id)) {
                return redirect('/');
            }

            $row = $this->marketplace_user::with(['skills', 'categories', 'user', "jobs" => function ($query) use ($employer) {
                $query->where("bc_jobs.company_id", "=", $employer->company->id);
            }])->where('id', $marketplace_user->id)->first();

            if($row->jobs->count() == 0) {
                return redirect('/');
            }
        } else {
            $row = $this->marketplace_user::with(['skills', 'categories', 'user'])->where('slug', $marketplace_user->id)->first();
        }

        if(!$row){
            $row = $marketplace_user;
        }

        if (empty($row)) {
            return redirect('/');
        } else {
            if ($row->allow_search == 'hide' && !is_applied($row->id) && $row->id != Auth::id()) {
                abort(403);
            }
        }



        $data = [
            'row'               => $row,
            'custom_title_page' => $title_page ?? "",
            "gallery"           => $row->getGallery(true),
            'header_transparent'=>true
        ];

        $layout = setting_item('marketplace_user_single_layout', 'v1');
        $style = "marketplace_user-single-$layout";
        $demo_layout = $request->get('_layout');
        if(!empty($demo_layout)){
            $demo_style = "marketplace_user-single-$demo_layout";
            $style = (View::exists("$this->marketplace_user::frontend.layouts.detail-ver.$demo_style")) ? $demo_style : "marketplace_user-single-$layout";
        }
        $data['style'] = $style;
        $this->setActiveMenu($row);

        get_user_view($row->id);

        return view('MarketplaceUser::frontend.detail', $data);
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'email'   => [
                'required',
                'max:255',
                'email'
            ],
            'name'    => ['required'],
            'message' => ['required']
        ]);
        /**
         * Google ReCapcha
         */
        if(ReCaptchaEngine::isEnable()){
            $codeCapcha = $request->input('g-recaptcha-response');
            if(!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)){
                $data = [
                    'status'    => 0,
                    'message'    => __('Please verify the captcha'),
                ];
                return response()->json($data, 200);
            }
        }
        $row = new MarketplaceUserContact($request->input());
        $row->status = 'sent';
        if ($row->save()) {
            $this->sendEmail($row);
            $data = [
                'status'    => 1,
                'message'    => __('Thank you for contacting us! We will get back to you soon'),
            ];
            return response()->json($data, 200);
        }
    }

    protected function sendEmail($contact){
        $userNotify = User::query()->where('id', $contact->origin_id)->first();
        if($userNotify){
            try {
                Mail::to($userNotify->email)->send(new NotificationMarketplaceUserContact($contact));

                $data = [
                    'id' => $contact->id,
                    'event'   => 'ContactToMarketplaceUser',
                    'to'      => 'marketplace_user',
                    'name' => $contact->name ?? '',
                    'avatar' => '',
                    'link' => route("marketplace_user.admin.myContact"),
                    'type' => 'apply_job',
                    'message' => __(':name have sent a contact to you', ['name' => $contact->name ?? ''])
                ];

                $userNotify->notify(new PrivateChannelServices($data));
            }catch (Exception $exception){
                Log::warning("Contact Marketplace User Send Mail: ".$exception->getMessage());
            }
        }
    }
}
