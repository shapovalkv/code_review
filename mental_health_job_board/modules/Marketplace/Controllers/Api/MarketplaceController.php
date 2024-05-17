<?php

namespace Modules\Marketplace\Controllers\Api;

use App\Enums\UserPermissionEnum;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Modules\FrontendController;
use Modules\Marketplace\Models\Marketplace;

class MarketplaceController extends FrontendController
{
    public function updateMarketplaceAttribute(Marketplace $marketplace = null, Request $request): JsonResponse
    {
        $this->checkPermission('marketplace_manage', UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE);

        $jobAttr = [
            'title',
            'content',
            'announcement_date',
            'video_url',
            'location_id',
            'map_lat',
            'map_lng',
            'map_zoom',
            'website',
            'cat_id',
            'employment_location'
        ];

        if ($marketplace !== null && $marketplace->id !== null) {

            $marketplace->fill($request->only($jobAttr));
            $result = $marketplace->save();

        } else {

            $saved = array_replace(Cache::get(auth()->id() . Marketplace::CACHE_KEY_DRAFT, []), $request->only($jobAttr));
            if ($request->input('announcement_status')) {
                $saved['announcement_status'] = json_encode($request->input('announcement_status'));
            } else {
                $saved['announcement_status'] = null;
            }

            $result = Cache::put(auth()->id() . Marketplace::CACHE_KEY_DRAFT, $saved);
        }

        return response()->json(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
