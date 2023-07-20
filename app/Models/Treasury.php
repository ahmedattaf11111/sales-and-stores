<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class Treasury extends Model
{
    protected $appends = ["ar_created_at", "en_created_at","ar_updated_at","en_updated_at"];

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
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
}
