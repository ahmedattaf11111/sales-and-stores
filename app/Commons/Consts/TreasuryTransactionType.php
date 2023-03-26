<?php

namespace App\Commons\Consts;

class TreasuryTransactionType
{
    const COLLECT = "COLLECT";
    const EXCHANGE = "EXCHANGE";
    const ALL = self::COLLECT . "," . self::EXCHANGE;
}
