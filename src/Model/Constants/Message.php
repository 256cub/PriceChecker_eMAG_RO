<?php

namespace App\Model\Constants;


class Message
{

    const PROCESS_RUNNING = "There is a running process already, wait couple of minutes...";
    const PRODUCT_NOT_FOUND = "Product ID not found in DB";
    const LOG_LEVEL = "error";
    const CAPTCHA = "Captcha Verification. Open in browser any page from eMag.com and complete captcha.";

    public static function getConstants()
    {
        $oClass = new \ReflectionClass(__CLASS__);

        return array_values($oClass->getConstants());
    }

}
