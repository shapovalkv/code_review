<?php
namespace Modules\Candidate\Controllers;

use App\Helpers\ReCaptchaEngine;
use App\Notifications\PrivateChannelServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Matrix\Exception;
use Modules\Candidate\Models\CandidateContact;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Candidate\Emails\NotificationCandidateContact;
use Modules\Contact\Models\Contact;
use Modules\FrontendController;
use Modules\Job\Models\JobCandidate;
use Modules\Language\Models\Language;
use Modules\Candidate\Models\Candidate;
use Modules\Candidate\Models\Category;
use Modules\Candidate\Models\CandidateTranslation;
use Modules\Location\Models\Location;
use Modules\Skill\Models\Skill;
use Modules\User\Models\User;
use Modules\User\Models\UserViews;

class CandidateController extends FrontendController
{


    /**
     * @var Candidate
     */
    private $candidate;
    private $defaultListCountSearch = 40;

    public function __construct(Candidate $candidate)
    {
        parent::__construct();
        $this->candidate = $candidate;
    }

    public function index(Request $request)
    {
        if((setting_item('candidate_public_policy') == "employer" || setting_item('candidate_public_policy') == "employer_applied") && !is_employer() && !is_employee()) {
            return redirect('/');
        }

        $list = call_user_func([Candidate::class,'search'],$request, $this->defaultListCountSearch);

        $markers = [];
        if (!empty($list)) {
            foreach ($list as $row) {
                $markers[] = [
                    "id"      => $row->id,
                    "title"   => $row->title,
                    "lat"     => (float)$row->map_lat,
                    "lng"     => (float)$row->map_lng,
//                    "gallery" => $row->getGallery(true),
                    "infobox" => view('Candidate::frontend.layouts.details.candidate-marker-infobox', ['row' => $row,'disable_lazyload'=>1,'wrap_class'=>'infobox-item'])->render(),
//                    'marker'  => asset('images/icons/png/pin.png'),
                    'customMarker' => view('Candidate::frontend.layouts.details.candidate-marker-avatar', ['row' => $row,'disable_lazyload'=>1])->render(),
                ];
            }
        }

        $limit_location = 10;
        $data = [
            'active_search_params' => $request->all(),
            'rows'               => $list,
            'list_locations'      => Location::where('status', 'publish')->limit($limit_location)->get()->toTree(),
            'list_categories'      => Category::where('status', 'publish')->orderBy('name')->get()->toTree(),
            'list_skills'      => Skill::where('status', 'publish')->get(),
            'min_max_price' => $this->candidate::getMinMaxPrice(),
            "filter"             => $request->query('filter'),
            "seo_meta"           => $this->candidate::getSeoMetaForPageList(),
            'markers'            => $markers,
            'list_search' => $this->defaultListCountSearch
        ];


        $layout = setting_item('candidate_list_layout', 'v1');
        $style = "candidate-list-$layout";
        $demo_layout = $request->get('_layout');
        if(!empty($demo_layout)){
            $demo_style = "candidate-list-$demo_layout";
            $style = (View::exists("$this->candidate::frontend.layouts.search.$demo_style")) ? $demo_style : "candidate-list-$layout";
        }
        $data['style'] = $style;
        $data['layout'] = $demo_layout ? $demo_layout : 'v1';
        if ($data['layout'] == 'v5') $data['footer_null'] = true;

        return view('Candidate::frontend.index', $data);
    }

    public function detail(Request $request, Candidate $candidate)
    {
        if((setting_item('candidate_public_policy') == "employer" || setting_item('candidate_public_policy') == "employer_applied")
            && !is_employer()
            && !is_employee()
            && !Auth::user()->candidate()->where('id', $candidate->id)->exists()){
                abort(404);
        }

        if(setting_item('candidate_public_policy') == "employer_applied") {

            $employer = User::with("company")->where("id", Auth::id())->first();
            if(empty($employer->company->id)) {
                return redirect('/');
            }

            $row = $this->candidate::with(['skills', 'categories', 'user', "jobs" => function ($query) use ($employer) {
                $query->where("bc_jobs.company_id", "=", $employer->company->id);
            }])->where('id', $candidate->id)->first();

            if($row->jobs->count() == 0) {
                return redirect('/');
            }
        } else {
            $row = $this->candidate::with(['skills', 'categories', 'user'])->where('slug', $candidate->id)->first();
        }

        if(!$row){
            $row = $candidate;
        }

        if (empty($row)) {
            return redirect('/');
        } else {
            if ($row->allow_search == 'hide' && !is_applied($row->id) && $row->id != Auth::id()) {
                abort(403);
            }
        }

        $translation = $row->translateOrOrigin(app()->getLocale());

        $data = [
            'row'               => $row,
            'translation'       => $translation,
            'model_category'    => Category::where("status", "publish"),
            'cv'                => CandidateCvs::query()->where('origin_id', $row->id)->where('is_default', 1)->first(),
            'custom_title_page' => $title_page ?? "",
            "gallery"           => $row->getGallery(true),
            'header_transparent'=>true,
            'seo_meta'          => $row->getSeoMetaWithTranslation(app()->getLocale(),$translation),
        ];

        $layout = setting_item('candidate_single_layout', 'v1');
        $style = "candidate-single-$layout";
        $demo_layout = $request->get('_layout');
        if(!empty($demo_layout)){
            $demo_style = "candidate-single-$demo_layout";
            $style = (View::exists("$this->candidate::frontend.layouts.detail-ver.$demo_style")) ? $demo_style : "candidate-single-$layout";
        }
        $data['style'] = $style;
        $this->setActiveMenu($row);

        get_user_view($row->id);

        return view('Candidate::frontend.detail', $data);
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
        $row = new CandidateContact($request->input());
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
                Mail::to($userNotify->email)->send(new NotificationCandidateContact($contact));

                $data = [
                    'id' => $contact->id,
                    'event'   => 'ContactToCandidate',
                    'to'      => 'candidate',
                    'name' => $contact->name ?? '',
                    'avatar' => '',
                    'link' => route("candidate.admin.myContact"),
                    'type' => 'apply_job',
                    'message' => __(':name have sent a contact to you', ['name' => $contact->name ?? ''])
                ];

                $userNotify->notify(new PrivateChannelServices($data));
            }catch (Exception $exception){
                Log::warning("Contact Candidate Send Mail: ".$exception->getMessage());
            }
        }
    }
}
