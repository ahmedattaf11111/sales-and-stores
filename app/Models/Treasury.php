<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class Treasury extends Model
{
    use Date;
    protected $guarded = [];
    public function updated_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function added_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function subTreasuries()
    {
        return $this->belongsToMany(
            Treasury::class,
            "sub_treasuries",
            "main_treasury_id",
            "sub_treasury_id"
        );
    }
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
}
