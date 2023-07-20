<?php

namespace App\Commons\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

trait Date
{
    public function getArCreatedAtAttribute()
    {
        Carbon::setLocale('ar');
        return (new Carbon($this->attributes["created_at"]))->diffForHumans();
    }
    public function getEnCreatedAtAttribute()
    {
        Carbon::setLocale('en');
        return (new Carbon($this->attributes["created_at"]))->diffForHumans();
    }
    public function getArUpdatedAtAttribute()
    {
        Carbon::setLocale('ar');
        return (new Carbon($this->attributes["updated_at"]))->diffForHumans();
    }
    public function getEnUpdatedAtAttribute()
    {
        Carbon::setLocale('en');
        return (new Carbon($this->attributes["updated_at"]))->diffForHumans();
    }
}
