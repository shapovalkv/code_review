<?php

namespace App\Http\Middleware;

use App\Resources\NotificationResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Modules\Candidate\Models\Candidate;
use Modules\Company\Models\Company;
use Modules\Core\Models\Menu;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'layouts.app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth' => function () use ($request) {
                $user = $request->user();
                return [
                    'user' => $user ? [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => $user->role,
                        'avatar' => $user->getAvatarUrl(),
                        'cvs' => is_candidate() && !is_admin() && $user->candidate ? $user->candidate->cvs->map(function ($cvs) {
                            return [
                                'id' => $cvs->id,
                                'file_name' => $cvs->media->file_name
                            ];
                        }) : null,
                    ] : null,
                    'notifications' => (is_candidate()) ?
                        NotificationResource::collection(Candidate::getNotifications(true)) :
                        NotificationResource::collection(Company::getNotifications(true)),
                ];
            },
            'header' => function () use ($request) {
                $header = (new Menu())->where('name', 'Main Menu')->first();
                return $header ? ['navigation_list' => json_decode($header->items, true)] : null;
            },
            'footer' => function () use ($request) {
                $footer = (new Menu())->where('name', 'Main Footer')->first();
                return $footer ? ['navigation_list' => json_decode($footer->items, true)] : null;
            },
            'flash' => function () use ($request) {
                $session = $request->session();
                return [
                    'success' => $session->get('success'),
                    'error' => $session->get('error'),
                ];
            },
        ]);
    }
}
