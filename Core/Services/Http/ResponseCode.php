<?php

namespace Core\Services\Http;

class ResponseCode
{
    public static int $code = 200;

    public static function setCode(int $code): void
    {
        self::$code = $code;
    }


    public static function getCode(): int
    {
        return self::$code;
    }
}