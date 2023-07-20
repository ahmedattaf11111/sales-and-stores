<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];
    use Date;
    protected $appends = ["ar_created_at", "en_created_at","ar_updated_at","en_updated_at"];	
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function supplierCategory()
    {
        return $this->belongsTo(SupplierCategory::class);
    }
}
