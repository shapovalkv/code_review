<?php

namespace Modules\Candidate\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Candidate\Models\Candidate;
use Modules\Candidate\Models\CandidateCategories;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Candidate\Models\CandidateSkills;
use Modules\Candidate\Models\Category;
use Modules\FrontendController;
use Modules\Location\Models\Location;
use Modules\Skill\Models\Skill;

class ProfileController extends FrontendController
{
    /**
     * @var Candidate
     */
    private $candidate;

    public function __construct(Candidate $candidate)
    {
        parent::__construct();
        $this->candidate = $candidate;
    }

    public function index()
    {
        if (!is_candidate()) abort(403);

        $user = auth()->user();

        $data = [
            'row' => $user->candidate ?? $this->candidate,
            'page_title' => __("Candidate Profile"),
            'user' => $user,
            'is_user_page' => true,
            'candidate_location' => Location::where('status', 'publish')->inRandomOrder()->limit(10)->get()->toTree(),
            'categories' => Category::orderBy('name')->get()->toTree(),
            'skills' => Skill::query()->where('status', 'publish')->get(),
            'cvs' => CandidateCvs::query()->where('origin_id', $user->id)->with('media')->get(),
            'menu_active' => 'user_profile',
            'languages' => config('languages.locales')
        ];
        return view('Candidate::frontend.profile.edit', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'               => 'required|max:255',
            'last_name'                => 'required|max:255',
            'title'                    => 'required|max:255',
            'experience_year'          => 'required|max:255',
            'bio'                      => 'required|max:1000',
            'location_id'              => 'required|exists:bc_locations,id',
            'experience.*.description' => 'max:1000',
            'education.*.from'         => 'date_format:m/Y',
            'education.*.to'           => 'nullable|date_format:m/Y',
            'experience.*.from'        => 'date_format:m/Y',
            'experience.*.to'          => 'nullable|date_format:m/Y',
            'award.*.from'             => 'date_format:m/Y',
        ], [
            'title.required'                => 'Please fill in your Current position',
            'experience_year.required'      => 'Please fill in your Experience',
            'location_id.required'          => 'Please fill in your location',
            'location_id.exists'            => 'This location is not found.',
            'bio.required'                  => 'Please fill in "About" field with your years of experience, industry, or skills',
            'education.*.from.date_format'  => 'Education Start Date format must be equal MM/YYYY. Example: 08/2023',
            'education.*.to.date_format'    => 'Education End Date format must be equal MM/YYYY. Example: 08/2023',
            'experience.*.from.date_format' => 'Experience Start Date format must be equal MM/YYYY. Example: 08/2023',
            'experience.*.to.date_format'   => 'Experience End Date format must be equal MM/YYYY. Example: 08/2023',
            'award.*.from.date_format'      => 'Training Date of Certificate Earned format must be equal MM/YYYY. Example: 08/2023',
        ]);

        $validator->validate();

        $user = auth()->user();
        $candidate = $user->candidate ?? $this->candidate;
        $candidate->id = $user->id;


        $user->fillByAttr([
            'first_name',
            'last_name',
            'avatar_id',
            'bio',
            'phone'

        ], $request->input());
        $user->save();

        $candidate->fillByAttr([
            'title',
//            'gallery',
//            'video',
//            'gender',
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
            'map_lat',
            'map_lng',
            'map_zoom',

            'education',
            'experience',
            'award',
            'social_media',
            'video_cover_id'

        ], $request->input());

        if (empty($request->input('allow_search')) && $candidate->never_saved_before ) {
            $candidate->allow_search = 'publish';
            $candidate->never_saved_before = 0;
            $request->session()->flash('hide_profile', 'Your Profile is Live. Which means it is partially visible to Employers in search mode. Your name and personal email/phone number is not visible until you apply for a position or accept a job invitation.');
            $request->session()->flash('hide_profile_v2', 'If you are no longer looking for a position, move the toggle switch below.');
        }

        if (!empty($request->input('location_id'))) {
            $candidate->location_id = $request->input('location_id');
        }

        $candidate->save();

        if (!empty($request->input('languages'))) {
            $candidate->languages = implode(',', $request->input('languages'));
        } else {
            $candidate->languages = '';
        }

        $candidate->saveOriginOrTranslation($request->query('lang'), true);


        $uploadedCandidate = CandidateCvs::query()->where('origin_id', $user->id)->pluck('file_id')->toArray();
        $cvUpload = $request->input('cvs', []);
        if (!empty($cvUpload)) {
            CandidateCvs::query()->where('origin_id', $user->id)->whereNotIn('file_id', $cvUpload)->delete();
            foreach ($cvUpload as $oneCv) {
                if (in_array($oneCv, $uploadedCandidate)) {
                    continue;
                }
                $cv = new CandidateCvs();
                $cv->file_id = $oneCv;
                $cv->origin_id = $user->id;
                $cv->is_default = 0;
                $cv->create_user = Auth::id();
                $cv->save();
            }

            //Update Default CV
            CandidateCvs::query()->where("origin_id", $user->id)->update(['is_default' => 0]);
            CandidateCvs::query()->where("origin_id", $user->id)->where('file_id', @$request->csv_default)->update(['is_default' => 1]);
        }

        if (!empty($request->skills)) {
            $cSkills = CandidateSkills::query()->where('origin_id', $user->id)->pluck('skill_id')->toArray();
            foreach ($request->skills as $skill) {
                $pos = array_search(intval($skill), $cSkills);
                if ($pos !== false) {
                    unset($cSkills[$pos]);
                } else {
                    DB::table('bc_candidate_skills')->insert([
                        'origin_id' => $user->id,
                        'skill_id' => $skill
                    ]);
                }
            }
            if (!empty($cSkills)) {
                CandidateSkills::query()->where('origin_id', $user->id)->whereIn('skill_id', $cSkills)->delete();
            }
        } else {
            CandidateSkills::query()->where('origin_id', $user->id)->delete();
        }

        if (!empty($request->categories)) {
            $cCats = CandidateCategories::query()->where('origin_id', $user->id)->pluck('cat_id')->toArray();
            foreach ($request->categories as $category) {
                $pos = array_search(intval($category), $cCats);
                if ($pos !== false) {
                    unset($cCats[$pos]);
                } else {
                    DB::table('bc_candidate_categories')->insert([
                        'origin_id' => $user->id,
                        'cat_id' => $category
                    ]);
                }
            }
            if (!empty($cCats)) {
                CandidateCategories::query()->where('origin_id', $user->id)->whereIn('cat_id', $cCats)->delete();
            }
        } else {
            CandidateCategories::query()->where('origin_id', $user->id)->delete();
        }

        return back()->with('success', __("Candidate profile saved"));
    }
}
