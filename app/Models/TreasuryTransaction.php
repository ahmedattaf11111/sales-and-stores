<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class TreasuryTransaction extends Model
{
    protected $appends = ["ar_created_at", "en_created_at", "ar_updated_at", "en_updated_at"];
    protected $guarded = [];
    use Date;
    public function added_by()
    {
        return $this->belongsTo(Admin::class);
    }
    public function treasury()
    {
        return $this->belongsTo(Treasury::class);
    }
    public function moveType()
    {
        return $this->belongsTo(MoveType::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }
    public function saleInvoice()
    {
        return $this->belongsTo(SaleInvoice::class);
    }
}
