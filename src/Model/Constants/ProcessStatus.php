<?php

namespace App\Model\Constants;


class ProcessStatus
{

    const NOT_STARTED = "NOT_STARTED";
    const RUNNING = "RUNNING";
    const DONE = "DONE";
    const ERROR = "ERROR";

    public static function getConstants()
    {
        $oClass = new \ReflectionClass(__CLASS__);

        return array_values($oClass->getConstants());
    }

}
