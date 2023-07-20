<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $guarded = [];
    use Date;

    protected $appends = ["ar_created_at", "en_created_at", "ar_closed_at", "en_closed_at"];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function treasury()
    {
        return $this->belongsTo(Treasury::class);
    }

    public function getArClosedAtAttribute()
    {
        Carbon::setLocale('ar');
        return $this->closed_at ? (new Carbon($this->attributes["closed_at"]))->diffForHumans() : null;
    }
    public function getEnClosedAtAttribute()
    {
        Carbon::setLocale('en');
        return $this->closed_at ? (new Carbon($this->attributes["closed_at"]))->diffForHumans() : null;
    }
}
