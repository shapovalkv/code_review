<?php
namespace Modules;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Candidate\Admin\CandidateController;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.admin')->except('getForSelect2');
    }

    public function checkPermission($permission = false)
    {
        if ($permission) {
            if (!Auth::id() or !Auth::user()->hasPermission($permission)) {
                abort(403);
            }
        }
    }

    public function hasPermission($permission)
    {
        return Auth::user()->hasPermission($permission);
    }
}
