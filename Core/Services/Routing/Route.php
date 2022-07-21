<?php
/**
 *=====================================================
 * Majestic Engine - by Zerxa Fun (Majestic Studio)   =
 *-----------------------------------------------------
 * @url: http://majestic-studio.ru/                   -
 *-----------------------------------------------------
 * @copyright: 2020 Majestic Studio and ZerxaFun      -
 *=====================================================
 *                                                    =
 *                                                    =
 *                                                    =
 *=====================================================
 */


namespace Core\Services\Routing;


use Core\Services\Client\Client;
use Core\Services\Session\Session;

/**
 * Class Route
 * @package Core\Services\Routing
 */
class Route extends RouteAbstract
{
    /**
     * Устанавливает маршрут GET.
     *
     * @param string  $uri     - URI для маршрутизации.
     * @param array   $options - варианты маршрута.
     * @return bool
     */
    public static function get(string $uri, array $options, bool $cache = false): bool
    {
        return static::add('get', $uri, $options, $cache);
    }

    /**
     * Устанавливает POST-маршрут.
     *
     * @param string  $uri     - URI для маршрутизации.
     * @param array   $options - варианты маршрута.
     * @return bool
     */
    public static function post(string $uri, array $options): bool
    {
        return static::add('post', $uri, $options);
    }

    /**
     * Устанавливает PUT-маршрут.
     *
     * @param string  $uri     - URI для маршрутизации.
     * @param array   $options - варианты маршрута.
     * @return bool
     */
    public static function put(string $uri, array $options): bool
    {
        return static::add('put', $uri, $options);
    }

    /**
     * Устанавливает PATCH-маршрут.
     *
     * @param string  $uri     - URI для маршрутизации.
     * @param array   $options - варианты маршрута.
     * @return bool
     */
    public static function patch(string $uri, array $options): bool
    {
        return static::add('patch', $uri, $options);
    }

    /**
     * Устанавливает DELETE-маршрут.
     *
     * @param string  $uri     - URI для маршрутизации.
     * @param array   $options - варианты маршрута.
     * @return bool
     */
    public static function delete(string $uri, array $options): bool
    {
        return static::add('delete', $uri, $options);
    }

    /**
     * Устанавливает CLI-маршрут.
     *
     * @param string  $uri     - URI для маршрутизации.
     * @param array   $options - варианты маршрута.
     * @return bool
     */
    public static function cli(string $uri, array $options): bool
    {
        return static::add('cli', $uri, $options);
    }
}

