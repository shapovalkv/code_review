<?php


namespace Modules\Candidate\Controllers\Api;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Candidate\Models\Candidate;
use Modules\FrontendController;

class ProfileController extends FrontendController
{

    public function updateCandidateAttribute(Request $request): JsonResponse
    {
        if (!is_candidate()) abort(403);

        /** @var User $user */
        $user = auth()->user();
        /** @var Candidate $candidate */
        $candidate = $user->candidate ?? (new Candidate);

        $userAttr = [
            'first_name',
            'last_name',
            'avatar_id',
            'bio',
            'phone'
        ];
        $candidateAtr = [
            'title',
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
            'location_id',
        ];

        $user->fill($request->only($userAttr));
        $candidate->fill($request->only($candidateAtr));

        if (!empty($candidate->website) && !str_contains($candidate->website, 'https://')) {
            $candidate->website = 'https://' . $candidate->website;
        }

        if (!empty($request->input('languages'))) {
            $candidate->languages = implode(',', $request->input('languages'));
        }

        $json = ['education', 'experience', 'award'];

        foreach ($json as $key) {
            if (!empty($request->input($key))) {
                $education = $candidate->$key;
                foreach ($request->input($key) as $k => $value) {
                    $education[$k] = $value;
                }
                $candidate->$key = $education;
            }
        }

        if (!empty($request->input('categories'))) {
            $candidate->categories()->sync($request->input('categories'));
        }

        $result = $candidate->save() && $user->save();

        return response()->json(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

}
