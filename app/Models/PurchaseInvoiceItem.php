<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceItem extends Model
{
    use Date;
    protected $guarded = [];
    protected $appends = ["ar_created_at", "en_created_at", "ar_updated_at", "en_updated_at"];

    public function updated_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function added_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function unitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }
}
