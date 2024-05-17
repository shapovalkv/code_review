<?php

namespace Modules\Company\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Company\Events\EmployerFilledCompanyProfile;
use Modules\Company\Models\Company;
use Modules\Company\Models\CompanyCategory as Category;
use Modules\Company\Models\CompanyTerm;
use Modules\Company\Models\CompanyTranslation;
use Modules\Core\Models\Attributes;
use Modules\FrontendController;
use Modules\Language\Models\Language;
use Modules\Location\Models\Location;
use Modules\Location\Services\LocationService;
use Modules\Skill\Models\Skill;

class ManageCompanyController extends FrontendController{

    protected $attributes;
    protected $location;
    protected $company;
    protected $company_translation;
    protected $category;
    protected $language;
    protected $company_term;

    public function __construct(){

        $this->attributes = Attributes::class;
        $this->company = Company::class;
        $this->location = Location::class;
        $this->category = Category::class;
        $this->company_translation = CompanyTranslation::class;
        $this->language = Language::class;
        $this->company_term = CompanyTerm::class;

    }

    public function companyProfile(Request $request){

        $this->checkPermission('employer_manage');

        $row = $this->company::where('owner_id', Auth::id())->first();

        if(empty($row)){
            $row = new $this->company;
            $row->owner_id = auth()->id();
            $row->save();
        }

        $translation = $row->translateOrOrigin($request->query('lang'));

        $data = [
            'row'  => $row,
            'categories'        => $this->category::get()->sortBy('name')->toTree(),
            'attributes'     => $this->attributes::where('service', 'company')->get(),
            'company_location'     => $this->location::where('status', 'publish')->get()->toTree(),
            'company_skills' => Skill::query()->where('status', 'publish')->where('skill_type', '=','company')->get(),
            'translation'  => $translation,
            'enable_multi_lang'=>true,
            'page_title'=>__("Company Profile"),
            'menu_active' => 'company_profile',
            "selected_terms"    => $row->companyTerm ? $row->companyTerm->pluck('term_id') : [],
            'is_user_page' => true,
        ];
        return view('Company::frontend.layouts.manageCompany.detail', $data);
    }

    public function companyUpdate(LocationService $locationService, Request $request){
        $this->checkPermission('employer_manage');
        $input = $request->input();

        $row = $this->company::where('owner_id', Auth::id())->first();

        if(empty($row)){
            return redirect(route('user.company.profile'))->with('error', __("No company found"));
        }

        $check = Validator::make($request->input(), [
            'name' => !empty($request->input('name')) && $request->input('name') != $row->name ?
                ['required', 'max:255', Rule::unique('bc_companies', 'name')]
                : 'required|max:255',
            'city' => 'required|max:255',
            'category_id' => 'required|max:255',
            'phone' => 'required',
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        if (!$check->validated()) {
            return back()->withInput($request->input());
        }

        if (is_default_lang()) {
            $request->validate([
                'map_location' => 'required',
            ], [
                'map_location.required' => __('Please select location from drop down or select place on the map'),
            ]);
        }

        $attr = [
            'name',
            'email',
            'phone',
            'website',
            'location_id',
            'category_id',
            'map_lat',
            'map_lng',
            'about',
            'social_media',
            'city',
            'state',
            'country',
            'address',
            'team_size',
            'is_featured',
            'zip_code',
        ];
        $input['team_size'] = !empty($input['team_size']) ? $input['team_size'] : 0;
        $row->fillByAttr($attr, $input);
        $row->location_id = $locationService->store($request);
        $row->status = 'publish';
        $row->allow_search = 1;

        if (!empty($request->input('avatar_id'))){
            $row->avatar_id = $request->avatar_id;
        }

        if (!empty($request->input('video_url'))){
            $row->video_url = $request->video_url;
        }

        if (!empty($request->input('gallery'))){
            $row->gallery = $request->gallery;
        }

        if (!empty($input['founded_in'])){
            $row->founded_in = Carbon::createFromFormat(get_date_format(), $input['founded_in']);
        }

        $row->skills()->sync($request->input('company_skills') ?? []);

        if($request->input('name')){
            $row->slug = Str::slug($request->input('name'), '-');
        }

        if (empty($row->is_completed) && $row->create_user == Auth::id()){
            event(new EmployerFilledCompanyProfile($row));
            $row->is_completed = true;
        }

        $res = $row->createSeoAndSave([
            'seo_title' => 'constructional_job_board_and_marketplace | '. $row->name,
            'seo_desc' => mb_strimwidth($row->about, 0,130, '...'),
            'seo_keywords' => null //TODO Create auto generating seo keywords with SEO team requirements
        ]);

        if ($res) {
            if (!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }
            $request->session()->forget(['complete_registration']);

            return back()->with('success',  __('Company updated') );
        }
    }

    public function saveTerms($row, $request)
    {
        if (empty($request->input('terms'))) {
            $this->company_term::where('company_id', $row->id)->delete();
        } else {
            $term_ids = $request->input('terms');
            foreach ($term_ids as $term_id) {
                $this->company_term::firstOrCreate([
                    'term_id' => $term_id,
                    'company_id' => $row->id
                ]);
            }
            $this->company_term::where('company_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }
}
