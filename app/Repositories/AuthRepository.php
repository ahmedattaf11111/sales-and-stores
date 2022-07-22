<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use App\Models\EmailVerification;
use App\Models\PasswordReset;

class AuthRepository
{
    public function create($user)
    {
        return User::create($user);
    }
    public function createEmailVerification($emailVerification)
    {
        $emailVerification["created_at"] = Carbon::now();
        EmailVerification::create($emailVerification);
    }
    public function updateEmailVerification($emailVerification)
    {
        $_emailVerification = EmailVerification::where("email", $emailVerification["email"])->first();
        if ($_emailVerification) {
            $_emailVerification->verification_code = $emailVerification["verification_code"];
            $_emailVerification["created_at"] = Carbon::now();
            $_emailVerification->save();
        }
    }
    public function verifyUser($email, $verificationCode, $expirationTime)
    {
        $queryBuilder = EmailVerification::where("email", $email)
            ->where("verification_code", $verificationCode)
            ->where('created_at', '>', Carbon::now()->subMinutes($expirationTime));
        $emailVerification = $queryBuilder->first();
        if ($emailVerification) {
            $queryBuilder->delete();
            $user = User::where("email", $email)->first();
            $user->email_verified_at =  Carbon::now();
            $user->save();
        }
        return $emailVerification;
    }
    public function insertResetPassword($email, $token)
    {
        $passwordReset = PasswordReset::where("email", $email)->first();
        if ($passwordReset) {
            $passwordReset->token = $token;
            $passwordReset->created_at =  Carbon::now();
            $passwordReset->save();
        } else {
            PasswordReset::insert(['email' => $email, 'token' => $token, 'created_at' => Carbon::now()]);
        }
        return $token;
    }
    public function getPasswordReset($token, $expirationTime)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->where('created_at', '>', Carbon::now()->subMinutes($expirationTime))->first();
        return $passwordReset;
    }
    public function changePassword($password, $email)
    {
        User::where('email', $email)
            ->update(['password' => bcrypt($password)]);
        PasswordReset::where('email', $email)->delete();
    }
    public function updateProfile($id, $userInput)
    {
        $user = User::find($id);
        $oldImage = $user->getImageName();
        $user->first_name = $userInput["first_name"];
        $user->last_name = $userInput["last_name"];
        $user->phone = isset($userInput["phone"]) ? $userInput["phone"] : "";
        $user->address = isset($userInput["address"]) ? $userInput["address"] : "";
        $user->city = isset($userInput["city"]) ? $userInput["city"] : "";
        $user->age = isset($userInput["age"]) ? $userInput["age"] : null;
        $user->education = isset($userInput["education"]) ? $userInput["education"] : "";
        $user->job = isset($userInput["job"]) ? $userInput["job"] : "";
        $user->about_me = isset($userInput["about_me"]) ? $userInput["about_me"] : "";
        $user->image = isset($userInput["image"]) ? $userInput["image"] : $oldImage;
        $user->save();
        return ["old_image" => $oldImage, "user" => $user];
    }
}
