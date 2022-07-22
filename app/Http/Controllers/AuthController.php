<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPassword;
use App\Mail\EmailVerification;
use App\Mail\ForgetPassword;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->middleware('auth', ['except' => ['login', 'register', 'forgetPassword', 'resetPassword']]);
        $this->middleware("verified", ["only" => ["userVerified"]]);
        $this->authRepository = $authRepository;
    }
    public function verifyToken()
    {
        //To verify the token in fornt end
        return ["valid" => true];
    }
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    public function register(RegisterRequest $request)
    {
        $user = $this->authRepository->create($request->input());
        $verificationCode = Str::random(5);
        $this->authRepository->createEmailVerification([
            "email" => $request->email,
            "verification_code" => $verificationCode,
        ]);
        Mail::to($user->email)->send(new EmailVerification($verificationCode));
        return $this->login($request);
    }
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $authUserEmail = JWTAuth::parseToken()->getPayload()->get("email");
        $verificationCodeValid = $this->authRepository
            ->verifyUser($authUserEmail, $request->verification_code, 15);
        if (!$verificationCodeValid) {
            return response()->json(["errorMessage" => "Verification code is not valid"], 400);
        }
    }
    public function resendVerificationCode()
    {
        $authUserEmail = JWTAuth::parseToken()->getPayload()->get("email");
        $verificationCode = Str::random(5);
        $this->authRepository->updateEmailVerification([
            "email" => $authUserEmail,
            "verification_code" => $verificationCode,
        ]);
        Mail::to($authUserEmail)->send(new EmailVerification($verificationCode));
    }
    public function userVerified()
    {
        //To check if user verified
        return response()->json(["verified" => true]);
    }
    public function forgetPassword(User $user)
    {
        $token = Str::random(40);
        $this->authRepository->insertResetPassword($user->email, $token);
        Mail::to($user->email)->send(new ForgetPassword(['user' => $user, 'token' => $token]));
    }
    public function resetPassword(ResetPassword $request)
    {
        $passwordReset = $this->authRepository->getPasswordReset($request->token, 15);
        if (empty($passwordReset)) {
            return response()->json(["error" => "Token isn't valid"], 400);
        }
        $this->authRepository->changePassword($request->password, $passwordReset->email);
        $request->merge(["email" => $passwordReset->email]);
        return $this->login($request);
    }
    public function logout()
    {
        //Make the current token invalid
        auth()->logout();
    }
    public function getCurrentUser()
    {
        return request()->user();
    }
    public function updateProfile(ProfileRequest $request)
    {
        if ($request->file("image")) {
            $request->merge(["image" => $request->file("image")->store("")]);
        }
        $authUserId = JWTAuth::parseToken()->getPayload()->get("id");
        $result = $this->authRepository->updateProfile($authUserId, $request->input());
        if ($request->file("image")) {
            Storage::delete($result["old_image"]);
        }
        return $result["user"];
    }
    //Commons
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}