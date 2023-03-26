<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function supplierCategory()
    {
        return $this->belongsTo(SupplierCategory::class);
    }
}
