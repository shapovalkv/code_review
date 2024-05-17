<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Location\Models\Location;
use Modules\User\Models\PopularSearch;

class PopularSearchService
{
    public function store($request)
    {
        if (!empty($request->keywords) || !empty($request->location)) {
            if (!empty($request->keywords)) {
                $existedSearchKeywords = PopularSearch::query()
                    ->where('module', '=', $request->path())
                    ->where('keywords', 'like', "%" . $request->keywords . "%")
                    ->first();
            }

            if (!empty($request->location)) {
                $existedSearchLocation = PopularSearch::query()
                    ->where('module', '=', $request->path())
                    ->Where('location', 'like', "%" . $request->location . "%")
                    ->Where('location_type', 'like', "%" . $request->location_type . "%")
                    ->first();
            }

            if (!empty($existedSearchKeywords)) {
                $existedSearchKeywords->increment('request_count');
                if (!empty($request->location) && empty($existedSearchLocation)) {
                    $existedSearchLocation = $this->createSearchLocationRecord($request);
                }
            }

            if (!empty($existedSearchLocation)) {
                $existedSearchLocation->increment('request_count');
                if (!empty($request->keywords) && empty($existedSearchKeywords)) {
                    $existedSearchKeywords = $this->createSearchKeywordRecord($request);
                }
            }

            if (!empty($request->keywords) && empty($existedSearchKeywords)) $this->createSearchKeywordRecord($request);
            if (!empty($request->location) && empty($existedSearchLocation)) $this->createSearchLocationRecord($request);
        }
    }

    public function createSearchKeywordRecord($request)
    {
        return PopularSearch::create([
            'module' => $request->path(),
            'keywords' => $request->keywords,
            'create_user' => Auth::check() ? Auth::user()->id : null,
        ]);
    }

    public function createSearchLocationRecord($request)
    {
        $state = null;
        if (!empty($request->location_state)){
            $state = Str::length($request->location_state) === 2 ? $request->location_state : collect(Location::STATES)->filter(function ($value, $key) use ($request) {
                return Str::contains(Str::lower($value), Str::lower($request->location));
            });
        }

        return PopularSearch::create([
            'module' => $request->path(),
            'location' => $request->location,
            'location_type' => $request->location_type ?? null,
            'location_state' => is_object($state) && $state->count() > 0 ? $state->keys()[0] : $state,
            'create_user' => Auth::check() ? Auth::user()->id : null,
        ]);
    }
}
