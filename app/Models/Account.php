<?php

namespace App\Models;

use App\Commons\Traits\Date;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use Date;
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
    public function parent()
    {
        return $this->belongsTo(Account::class);
    }
    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }
}
