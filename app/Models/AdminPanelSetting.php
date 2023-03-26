<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;
use App\Commons\Traits\Image;

class AdminPanelSetting extends Model
{
    protected $guarded = [];
    use Image, Date;
    public function updated_by()
    {
        return $this->belongsTo(Admin::class);
    }
}
