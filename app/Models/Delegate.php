<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    protected $guarded = [];
    use Date;
    protected $appends = ["ar_created_at", "en_created_at","ar_updated_at","en_updated_at"];	

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
