<?php

namespace App\Models;

use App\Commons\Traits\Date;
use App\Commons\Traits\Image;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $appends = ["ar_created_at", "en_created_at","ar_updated_at","en_updated_at"];

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
    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }
    public function mainUnitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }
    public function subUnitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
