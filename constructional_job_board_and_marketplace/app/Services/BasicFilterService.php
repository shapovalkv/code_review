<?php

namespace App\Services;

use App\Resources\PopularSearchesResource;
use Illuminate\Support\Facades\App;
use Modules\User\Models\PopularSearch;
use Modules\User\Models\UserWishList;

class BasicFilterService
{
    const POPULARSEARCHVALUE = 6;

    public function experience($experienceRequest)
    {
        return [
            'items' => [
                ['name' => 'Less than a year', 'slug' => 'fresh'],
                ['name' => '1 Year', 'slug' => '1'],
                ['name' => '2 Years', 'slug' => '2'],
                ['name' => '3 Years', 'slug' => '3'],
                ['name' => '4 Years', 'slug' => '4'],
                ['name' => '5 Years', 'slug' => '5'],
                ['name' => 'More than 5 Years', 'slug' => 'tempered'],
            ],
            'values' => $experienceRequest,
        ];
    }

    public function seniorityLevel($seniorityLevelRequest)
    {
        return [
            'items' => [
                ['name' => 'Commercial', 'slug' => 'commercial'],
                ['name' => 'Residential', 'slug' => 'residential'],
                ['name' => 'Newbie / Journeyman', 'slug' => 'newbie'],
            ],
            'values' => $seniorityLevelRequest,
        ];
    }

    public function popularSearchKeywords($keywordsRequest, $modelType = null)
    {
        return [
            'items' => !empty($modelType) ? PopularSearch::query()
                ->where('module', '=', $modelType)
                ->whereNotNull('keywords')
                ->orderByDesc('request_count')
                ->take(self::POPULARSEARCHVALUE)
                ->get()
                ->map(function ($popularSearches) {
                    $popularSearches->keywords =  ucfirst($popularSearches->keywords);
                    return $popularSearches->keywords;
                }) : null,
            'values' => $keywordsRequest
        ];
    }

    public function popularSearchLocation($locationRequest, $modelType = null)
    {
        $popularSearches = null;
        if (empty($modelType))
        {
            return [ 'items' => $popularSearches, 'values' => $locationRequest, 'errors' => true];
        } else {
            $popularSearches =  PopularSearch::query()
                ->where('module', '=', $modelType)
                ->whereNotNull('location')
                ->whereNotNull('location_type')
                ->whereNotNull('location_state')
                ->orderByDesc('request_count')
                ->take(self::POPULARSEARCHVALUE)
                ->get();
        }
        return [
            'items' => PopularSearchesResource::collection($popularSearches),
            'values' => $locationRequest
        ];
    }

    public function searchLocationType($locationTypeRequest)
    {
        return [
            'values' => $locationTypeRequest
        ];
    }

    public function searchLocationState($locationStateRequest)
    {
        return [
            'values' => $locationStateRequest
        ];
    }

    public function orderby($orderByRequest)
    {
        return [
            'items' => [
                ['name' => 'Default', 'slug' => ''],
                ['name' => 'Newest ', 'slug' => 'new'],
                ['name' => 'Oldest', 'slug' => 'old'],
                ['name' => 'Name [a->z]', 'slug' => 'name_high'],
                ['name' => 'Name [z->a]', 'slug' => 'name_low'],
            ],
            'values' => $orderByRequest
        ];
    }

    public function limit($limitRequest)
    {
        return [
            'items' => [
                ['name' => 'Show 10', 'slug' => 10],
                ['name' => 'Show 20', 'slug' => 20],
                ['name' => 'Show 30', 'slug' => 30],
                ['name' => 'Show 40', 'slug' => 40],
                ['name' => 'Show 50', 'slug' => 50],
                ['name' => 'Show 100', 'slug' => 100],
            ],
            'values' => $limitRequest
        ];
    }

    public function gender($genderRequest)
    {
        return [
            'items' => [
                ['name' => 'Male', 'slug' => 'male'],
                ['name' => 'Female', 'slug' => 'female'],
                ['name' => 'Both', 'slug' => 'both'],
            ],
            'values' => $genderRequest,
        ];
    }

    public function active($activeRequest, $modelName, $idFromModel)
    {
        $model = App::make($modelName);
        return [
            'items' => [
                [
                    'name' => "All Posts",
                    'slug' => '',
                    'count' => $modelName::query()
                        ->when($model->type == 'equipment', function ($query) use ($idFromModel) {
                            $query->where('author_id', '=', $idFromModel);
                        })
                        ->when($model->type == 'job', function ($query) use ($idFromModel) {
                            $query->where('company_id', '=', $idFromModel);
                        })
                        ->whereNull('deleted_at')
                        ->count(),
                ],
                [
                    'name' => 'Active',
                    'slug' => 'publish',
                    'count' => $modelName::query()
                        ->when($model->type == 'equipment', function ($query) use ($idFromModel) {
                            $query->where('author_id', '=', $idFromModel);
                        })
                        ->when($model->type == 'job', function ($query) use ($idFromModel) {
                            $query->where('company_id', $idFromModel);
                        })
                        ->whereNull('deleted_at')
                        ->where('status', 'publish')
                        ->count(),
                ],
                [
                    'name' => 'Inactive',
                    'slug' => 'draft',
                    'count' => $modelName::query()
                        ->when($model->type == 'equipment', function ($query) use ($idFromModel) {
                            $query->where('author_id', '=', $idFromModel);
                        })
                        ->when($model->type == 'job', function ($query) use ($idFromModel) {
                            $query->where('company_id', $idFromModel);
                        })
                        ->whereNull('deleted_at')
                        ->where('status', 'draft')
                        ->count(),
                ],
            ],
            'values' => $activeRequest,
        ];
    }

    public function activeWishlist($activeRequest, $idFromModel)
    {
        return [
            'items' => [
                [
                    'name' => 'Candidate',
                    'slug' => 'candidate',
                    'count' => UserWishList::query()
                        ->where('create_user', $idFromModel)
                        ->where('object_model', '=', 'candidate')
                        ->where('user_id', $idFromModel)
                        ->count(),
                ],
                [
                    'name' => 'Job',
                    'slug' => 'job',
                    'count' => UserWishList::query()
                        ->where('create_user', $idFromModel)
                        ->where('object_model', '=','job')
                        ->where('user_id', $idFromModel)
                        ->count(),
                ],
                [
                    'name' => 'Company',
                    'slug' => 'company',
                    'count' => UserWishList::query()
                        ->where('create_user', $idFromModel)
                        ->where('object_model', '=','company')
                        ->where('user_id', $idFromModel)
                        ->count(),
                ],
                [
                    'name' => 'Equipment',
                    'slug' => 'equipment',
                    'count' => UserWishList::query()
                        ->where('create_user', $idFromModel)
                        ->where('object_model', '=','equipment')
                        ->where('user_id', $idFromModel)
                        ->count(),
                ],
            ],
            'values' => $activeRequest,
        ];
    }

    public function sponsored($sponsoredRequest)
    {
        return [
            'items' => [
                ['name' => 'Yes', 'slug' => '1'],
                ['name' => 'No', 'slug' => '0'],
            ],
            'values' => $sponsoredRequest,
        ];
    }

    public function date($dateRequest)
    {
        return [
            'items' => [
                ['name' => 'Today', 'slug' => 'last_1'],
                ['name' => 'This week', 'slug' => 'last_7'],
                ['name' => 'This month', 'slug' => 'last_30'],
                ['name' => 'Set the range', 'slug' => 'range'],
            ],
            'values' => $dateRequest,
        ];
    }

    public function applicantStatus($applicantStatusRequest)
    {
        return [
            'items' => [
                ['name' => 'Contract offered', 'slug' => 'pending'],
                ['name' => 'Hired', 'slug' => 'publish'],
                ['name' => 'Interested', 'slug' => 'interested'],
                ['name' => 'Interview', 'slug' => 'interview'],
                ['name' => 'Rejected', 'slug' => 'rejected'],
                ['name' => 'Phone interview', 'slug' => 'phone_interview'],
            ],
            'values' => $applicantStatusRequest,
        ];
    }

    public function pagination($modelItems)
    {
        return [
            'pagination' => [
                'total' => $modelItems->total(),
                'last_page' => $modelItems->lastPage(),
                'per_page' => $modelItems->perPage(),
                'current_page' => $modelItems->currentPage(),
            ],
        ];
    }

    public function wishListTab($tabRequest)
    {
        return [
            'items' => [
                ['candidate' => route('user.wishList.index', ['active' => 'candidate'])],
                ['job' => route('user.chat.index', ['active' => 'job'])],
                ['equipment' => route('user.chat.index', ['active' => 'equipment'])],
                ['company' => route('user.chat.index', ['active' => 'company'])],
            ],
            'values' => $tabRequest,
        ];
    }
}
