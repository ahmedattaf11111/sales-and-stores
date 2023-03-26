<?php

namespace App\Commons\Consts;

class PurchaseInvoiceType
{
    const CASH = "CASH";
    const DEFERRED = "DEFERRED";
    const ALL = self::CASH . "," . self::DEFERRED;
}
