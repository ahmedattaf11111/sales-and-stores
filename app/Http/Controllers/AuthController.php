<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:admin")->except("login");
    }
    public function verifyToken()
    {
        //To verify the token in fornt end
        return ["valid" => true];
    }
    public function hasPermission($screenName)
    {
        return response()->json(["has_permission" => request()->user()->hasAnyPermission(["super admin", $screenName])]);
    }
    public function getCurrentPermissions()
    {
        return request()->user()->permissions;
    }
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->guard("admin")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
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
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
