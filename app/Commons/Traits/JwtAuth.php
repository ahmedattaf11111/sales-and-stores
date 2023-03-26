<?php

namespace App\Commons\Traits;

use Illuminate\Support\Facades\Hash;

trait JwtAuth
{
    public function setPasswordAttribute($value)
    {
        $this->attributes["password"] = Hash::make($value);
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "name" => $this->name,
        ];
    }
}
