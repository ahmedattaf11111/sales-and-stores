<?php

namespace App\Commons\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

trait Date
{
    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes["updated_at"])
            ->setTimezone('Africa/Cairo')
            ->format("Y/m/d h:i A");
    }
    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes["created_at"])
            ->setTimezone('Africa/Cairo')
            ->format("Y/m/d h:i A");
    }
}
