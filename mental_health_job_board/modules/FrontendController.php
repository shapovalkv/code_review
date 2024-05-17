<?php

namespace Modules;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function __construct()
    {

    }

    public function checkPermission(string ...$permissions): void
    {
        if (!Auth::id() || !Auth::user()->hasPermission($permissions)) {
            abort(403);
        }
    }

    public function hasPermission($permission): bool
    {
        if (!Auth::id()) return false;
        return Auth::user()->hasPermission($permission);
    }
}
