<?php

namespace App\Services;


use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class GoogleSocialiteService
{
    public function authorize($socialiteUser)
    {
        try {

            $findUser = User::where('social_id', $socialiteUser->id)->first();

            if($findUser){

                Auth::login($findUser);

                return redirect(route('profile.edit'));

            }else{
                $newUser = User::create([
                    'first_name' => $socialiteUser->user['given_name'],
                    'last_name' => $socialiteUser->user['family_name'],
                    'email' => $socialiteUser->email,
                    'social_id'=> $socialiteUser->id,
                    'social_type'=> 'google',
                    'password' => encrypt('my-google'),
                ]);

                $newUser->assignRole(User::ROLE_CUSTOMER);

                Auth::login($newUser);

                return redirect(route('profile.edit'));
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
