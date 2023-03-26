<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class SaleInvoice extends Model
{
    use Date;
    protected $guarded = [];

    public function customer()
    {

        return $this->belongsTo(Customer::class);
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
