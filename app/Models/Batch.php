<?php

namespace App\Models;

use App\Commons\Traits\Date;
use App\Commons\Traits\Image;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use Date, Image;
    protected $guarded = [];
    public function updated_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function added_by()
    {
        return $this->belongsTo(Admin::class);
    }
}
