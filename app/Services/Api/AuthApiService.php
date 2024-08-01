<?php
/*
 *  ______     __  __     ______     ______     __   __   ______     __
 * /\  == \   /\ \/\ \   /\___  \   /\___  \   /\ \ / /  /\  ___\   /\ \
 * \ \  __<   \ \ \_\ \  \/_/  /__  \/_/  /__  \ \ \'/   \ \  __\   \ \ \____
 *  \ \_____\  \ \_____\   /\_____\   /\_____\  \ \__|    \ \_____\  \ \_____\
 *   \/_____/   \/_____/   \/_____/   \/_____/   \/_/      \/_____/   \/_____/
 *
 * Made By: Mauro Gama
 *
 * ♥ BY Buzzers: BUZZVEL.COM
 * Last Update: 2022.6.10
 */

namespace App\Services\Api;

use App\Mail\UserForgetPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthApiService
{


    public function forgetPassword(string $email)
    {
        $email = config('app.key') . $email;
        $emailDecrypted = base64_encode($email);
        $user = User::where('email', $emailDecrypted)->first();
        if (!$user) {
            throw new \Exception(__('auth')['email_not_found']);
        }

        $token = Password::createToken($user);

        Mail::to($user->email)->send(new UserForgetPassword($user, $token));

        return __('auth')['email_sent'];
    }

    public function resetPassword($validation)
    {
        $email = config('app.key') . $validation['email'];
        $emailDecrypted = base64_encode($email);
        $user = User::where('email', $emailDecrypted)->first();
        if (!$user) {
            throw new \Exception(__('auth')['email_not_found']);
        }

        if(!Password::tokenExists($user, $validation['token'])){
            throw new \Exception(__('auth')['invalid_token']);
        }
        $user['password'] = Hash::make($validation['password']);
        $user->save();

        return __('auth')['password_changed'];
    }
}
