<?php


namespace Modules\User\Controllers;


use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\FrontendController;

class PasswordController extends FrontendController
{

    use ResetsPasswords;

    public function changePassword(Request $request)
    {
        $data = [
            'breadcrumbs' => [
                [
                    'name'  => __('Change Password'),
                    'class' => 'active'
                ]
            ],
            'page_title'  => __("Change Password"),
            'menu_active' => 'change_password'
        ];
        return view('User::frontend.changePassword', $data);
    }

    public function changePasswordUpdate(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", __("Your current password does not matches with the password you provided. Please try again."));
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", __("New Password cannot be same as your current password. Please choose a different password."));
        }
        $request->validate([
            'current-password' => 'required',
            'new-password'     => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $this->resetPassword($user,$request->input('new-password'));

        return redirect()->back()->with('success', __('Password changed successfully !'));
    }

    public function reset(Request $request)
    {
        $request->validate($this->resetRules(), $this->validationErrorMessages());

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);

        return redirect('/');
    }

    protected function resetRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }

}
