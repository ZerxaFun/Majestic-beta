<?php

namespace Core\Services\Routing\Response;

class ConditionModule
{
    public static string $module;

    public static string $controller;

    public static string $action;

    public static string $uri;

    public static string $method;

    public static int $codeStatus;


    /**
     * @return int
     */
    public static function getCodeStatus(): int
    {
        return self::$codeStatus;
    }

    /**
     * @param int $codeStatus
     */
    public static function setCodeStatus(int $codeStatus): void
    {
        self::$codeStatus = $codeStatus;
    }
}