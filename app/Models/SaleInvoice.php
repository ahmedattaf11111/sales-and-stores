<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class SaleInvoice extends Model
{
    use Date;
    protected $guarded = [];
    protected $casts = [
        "invoice_number" => "integer",
        "total_sale_price" => "float",
        "is_discount_percent" => "integer",
        "discount" => "float",
        "is_tax_percent" => "integer",
        "tax" => "float",
        "paid_amount" => "float",
        "is_deferred" => "integer",
        "is_approved" => "integer",
        "is_delegate_commission_percent" => "integer",
        "delegate_commission" => "integer",
    ];
    protected $appends = ["ar_created_at", "en_created_at", "ar_updated_at", "en_updated_at"];

    public function customer()
    {

        return $this->belongsTo(Customer::class);
    }

    public function delegate()
    {

        return $this->belongsTo(Delegate::class);
    }

    public function InvoiceCategory()
    {
        return $this->belongsTo(InvoiceCategory::class);
    }
    public function added_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function approved_by()
    {
        return $this->belongsTo(Admin::class);
    }
}
