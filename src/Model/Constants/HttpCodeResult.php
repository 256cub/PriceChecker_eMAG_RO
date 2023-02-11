<?php

namespace App\Model\Constants;


class HttpCodeResult
{

    const HTTP_OK = "HTTP/1.1 200 OK";
    const HTTP_AUTH_REQUIRED = "HTTP/1.1 511 ";

    public static function getConstants()
    {
        $oClass = new \ReflectionClass(__CLASS__);

        return array_values($oClass->getConstants());
    }

}
