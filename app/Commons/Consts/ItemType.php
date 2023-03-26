<?php

namespace App\Commons\Consts;

class ItemType
{
    const STORE = "STORE"; //With no expiration date
    const HAS_EXPIRATION_DATE = "HAS_EXPIRATION_DATE";
    const CUSTODY = "CUSTODY"; //Provice the product to the client for specific period of time
    const ALL = self::STORE . "," . self::HAS_EXPIRATION_DATE . "," . self::CUSTODY;
}
