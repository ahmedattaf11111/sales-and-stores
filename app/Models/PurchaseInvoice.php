<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use Date;
    protected $casts = [
        "invoice_number" => "integer",
        "total_purchase_price" => "float",
        "is_discount_percent" => "integer",
        "discount" => "float",
        "is_tax_percent" => "integer",
        "tax" => "float",
        "paid_amount" => "float",
        "is_deferred" => "integer",
        "is_approved" => "integer",
    ];
    protected $appends = ["ar_created_at", "en_created_at", "ar_updated_at", "en_updated_at"];
    protected $guarded = [];
    public function updated_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function added_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function approved_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
