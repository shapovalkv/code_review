<?php

namespace Modules\Company\Controllers;

use App\Enums\UserPermissionEnum;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Company\Models\Company;
use Modules\Company\Models\CompanyCategory as Category;
use Modules\Company\Models\CompanyOffices;
use Modules\Company\Models\CompanyTerm;
use Modules\Company\Models\CompanyTranslation;
use Modules\Core\Models\Attributes;
use Modules\FrontendController;
use Modules\Language\Models\Language;
use Modules\Location\Models\Location;

class ManageCompanyController extends FrontendController
{

    protected $attributes;
    protected $location;
    protected $company;
    protected $company_translation;
    protected $category;
    protected $language;
    protected $company_term;

    public function __construct()
    {

        $this->attributes = Attributes::class;
        $this->company = Company::class;
        $this->location = Location::class;
        $this->category = Category::class;
        $this->company_translation = CompanyTranslation::class;
        $this->language = Language::class;
        $this->company_term = CompanyTerm::class;

    }

    public function companyProfile(Request $request): View
    {

        $this->checkPermission('employer_manage');
        /** @var User $user */
        $user = auth()->user();

        $row = Company::query()->where('owner_id', $user->parent ? $user->parent->id : $user->id)->first();

        if (empty($row)) {
            $row = new $this->company;
            $row->owner_id = auth()->id();
            $row->email = $user->email;
            $row->name = $user->display_name;
            $row->save();
        }

        $translation = $row->translateOrOrigin($request->query('lang'));

        $data = [
            'row'               => $row,
            'offices'           => json_encode($row->offices->map(function ($relations) {
                $relations->name = $relations->location ? $relations->location->name : '';
                return $relations->only(['id', 'name', 'is_main', 'location_id', 'map_lat', 'map_lng', 'map_zoom']);
            })),
            'categories'        => $this->category::orderBy('name')->get()->toTree(),
            'attributes'        => $this->attributes::where('service', 'company')->get(),
            'company_location'  => $this->location::where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'translation'       => $translation,
            'enable_multi_lang' => true,
            'page_title'        => __("Company Profile"),
            'menu_active'       => 'company_profile',
            "selected_terms"    => $row->companyTerm ? $row->companyTerm->pluck('term_id') : [],
            'is_user_page'      => true,
        ];
        return view('Company::frontend.layouts.manageCompany.detail', $data);
    }

    public function companyUpdate(Request $request)
    {
        $this->checkPermission('employer_manage');

        /** @var User $user */
        $user = auth()->user();

        $row = Company::query()->where('owner_id', $user->parent ? $user->parent->id : $user->id)->first();

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);
        $validator->validate();


        $offices_validator = Validator::make(array('offices' => json_decode($request->offices, true)), [
            'offices'               => 'required|array',
            'offices.*.location_id' => 'required|exists:bc_locations,id',
        ], [
            'offices.*.location_id.required' => 'Please fill in your location (Select a location from the location dropdown).',
            'offices.*.location_id.exists'   => 'This location is not found.',
        ]);

        $offices_validator->validate();
        $input = $request->input();

        if (empty($row)) {
            return redirect(route('user.company.profile'))->with('error', __("No company found"));
        }

        $user = auth()->user();
        $user->fillByAttr([
            'avatar_id',
        ], $request->input());
        $user->save();

        if (empty($row->name) && !empty($request->input('name'))) {
            $row->slug = Str::slug($request->input('name'), '-');
            $row->save();
        }
        foreach ($input['social_media'] as $mediaName => $media) {
            if ($mediaName == 'skype') continue;
            if (!empty($media) && !str_contains($media, 'https://')) {
                $input['social_media'][$mediaName] = 'https://' . $media;
            }
        }

        $attr = [
            'name',
            'email',
            'phone',
            'website',
            'avatar_id',
//            'category_id',
            'status',
            'about',
            'social_media',
            'city',
            'state',
            'country',
            'address',
            'team_size',
            'is_featured',
            'zip_code',
            'allow_search',
            'w2_california'
        ];
        $input['team_size'] = !empty($input['team_size']) ? $input['team_size'] : 0;

        $row->fillByAttr($attr, $input);
        if (!empty($row->website) && !str_contains($row->website, 'https://')) {
            $row->website = 'https://' . $row->website;
        }
        if ($request->input('slug')) {
            $row->slug = $request->input('slug');
        }

        if (!empty($input['founded_in'])) {
            $row->founded_in = Carbon::createFromFormat(get_date_format(), $input['founded_in']);
        }

        $res = $row->saveOriginOrTranslation($request->query('lang'), true);

        if (!empty($offices = json_decode($request->offices, true))) {
            CompanyOffices::query()->where('company_id', $user->company->id)->delete();
            foreach ($offices as $office) {
                DB::table('bc_company_offices_locations')->insert([
                    'company_id'  => $user->company->id,
                    'location_id' => $office['location_id'],
                    'is_main'     => $office['is_main'],
                    'map_lat'     => $office['map_lat'],
                    'map_lng'     => $office['map_lng'],
                    'map_zoom'    => $office['map_zoom'],
                ]);
            }
        } else {
            CompanyOffices::query()->where('company_id', $user->company->id)->delete();
        }

        if ($res) {
            if (!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }
            return back()->with('success', __('Company updated'));
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
                    'term_id'    => $term_id,
                    'company_id' => $row->id
                ]);
            }
            $this->company_term::where('company_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }
}
