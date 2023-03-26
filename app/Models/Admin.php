<?php

namespace App\Models;

use App\Commons\Traits\Date;
use App\Commons\Traits\JwtAuth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable, JwtAuth,Date;
    protected $guarded = [];
    protected $hidden = [
        'password',
    ];
    public function treasuries()
    {
        return $this->belongsToMany(Treasury::class);
    }
    public function updated_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function added_by()
    {
        return $this->belongsTo(Admin::class);
    }
}
