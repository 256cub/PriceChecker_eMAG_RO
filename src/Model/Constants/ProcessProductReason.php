<?php

namespace App\Model\Constants;


class ProcessProductReason
{

    const LOWER_THAN_MINIM = "Price cannot be updated because the value goes under the minimum.";
    const PRICE_IS_LOWEST = "Current price is lowest.";
    const PRICE_UPDATE = "Price should be updated.";
    const NO_RELATED_PRODUCT = "Not found related products.";

    public static function getConstants()
    {
        $oClass = new \ReflectionClass(__CLASS__);

        return array_values($oClass->getConstants());
    }

}
