<?php

namespace App\Models;

use App\Commons\Traits\Date;
use App\Commons\Traits\JwtAuth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable, JwtAuth,Date,HasRoles;
    protected $guarded = [];
    protected $appends = ["ar_created_at", "en_created_at","ar_updated_at","en_updated_at"];	
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
