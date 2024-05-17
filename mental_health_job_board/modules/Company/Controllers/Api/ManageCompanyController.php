<?php

namespace Modules\Company\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Company\Models\Company;
use Modules\Company\Models\CompanyOffices;
use Modules\FrontendController;

class ManageCompanyController extends FrontendController
{
    public function updateCompanyAttribute(Request $request): JsonResponse
    {
        $this->checkPermission('employer_manage');

        /** @var User $user */
        $user = auth()->user();
        $company = $user->company;

        $allowedAttr = [
            'name',
            'email',
            'phone',
            'website',
            'avatar_id',
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

        if ($request->input('social_media')) {
            $socialMedia = $company->social_media;
            foreach ($request->input('social_media') as $k => $item) {
                if ($k !== 'skype' && !str_contains($item, 'https://')) {
                    $item = 'https://' . $item;
                }
                $socialMedia[$k] = $item;
            }
            $company->social_media = $socialMedia;
        } elseif ($request->input('location')) {
            $officeData = $request->input('location');
            $officeData['company_id'] = $company->id;
            if (empty($request->input('location')['id'])) {
                unset($officeData['id']);
            } else {
                $officeData = ['id' => $request->input('location')['id']];
            }
            /** @var CompanyOffices $officeLocation */
            $officeLocation = CompanyOffices::query()->firstOrNew($officeData);
            $officeLocation->fill($request->input('location'));
            $officeLocation->company()->associate($company);
            $officeLocation->save();
        } else {
            $company->fill($request->only($allowedAttr));
        }

        if (!empty($company->website) && !str_contains($company->website, 'https://')) {
            $company->website = 'https://' . $company->website;
        }
        $result = $company->save();

        return response()->json(['success' => $result, 'office_id' => isset($officeLocation) ? $officeLocation->id : null], $result ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);

    }

    public function deleteCompanyOffice(CompanyOffices $companyOffice): JsonResponse
    {
        $companyOffice->deleteOrFail();

        return response()->json(['success' => true]);
    }
}
