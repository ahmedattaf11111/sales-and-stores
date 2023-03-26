<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $guarded = [];
    use Date;
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function treasury()
    {
        return $this->belongsTo(Treasury::class);
    }
}
