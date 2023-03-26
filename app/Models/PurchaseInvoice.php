<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
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
    public function approved_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
