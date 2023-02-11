<?php

namespace App\Model\Constants;


class ProcessProductStatus
{

    const PRICE_SHOULD_BE_UPDATED = "PRICE_SHOULD_BE_UPDATED";
    const PRICE_UPDATED = "PRICE_UPDATED";
    const PRICE_SHOULD_NOT_BE_UPDATED = "PRICE_SHOULD_NOT_BE_UPDATED";
    const ERROR = "ERROR";

    public static function getConstants()
    {
        $oClass = new \ReflectionClass(__CLASS__);

        return array_values($oClass->getConstants());
    }

}
