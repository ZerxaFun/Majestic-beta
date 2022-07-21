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


use Core\Services\Http\Header;
use Core\Services\Http\ResponseCode;
use Core\Services\Http\Uri;
use Core\Services\Routing\Modules\Language;
use Core\Services\Routing\Response\ConditionModule;
use Exception;

/**
 * Class Repository
 * @package Core\Services\Routing
 */
class Repository
{
    /**
     * @var array - хранимые маршруты.
     */
    public static array $stored;


    /**
     * Получить сохраненные маршруты.
     *
     * @return array
     */
    public static function stored(): array
    {
        return static::$stored;
    }

    /**
     * Добавление нового маршрута в хранилище.
     *
     * @param string $method - метод запроса маршрута.
     * @param string $uri - URI маршрута.
     * @param array $options - варианты маршрута.
     * @param bool $cache
     * @return void
     *
     * @throws Exception
     */
    public static function store(string $method, string $uri, array $options, bool $cache = false): void
    {
        $languageTag = Language::module();

        foreach ($languageTag[$options['module']]['tag'] as $tag => $languages) {
            if($languageTag[$options['module']]['default']['module'] === $tag) {
                $prefix = '';
            } else {
                $prefix = $tag . '/';
            }

            static::$stored[$options['module']]['route'][$tag][$prefix . $uri][$method] =  array_merge($options, ['cache' => $cache]);
        }

    }

    /**
     * Получить сохраненный маршрут.
     *
     * @param string $method - метод маршрута.
     * @param string $uri - URI маршрута.
     * @return array
     */
    public static function retrieve(string $method, string $uri): array
    {
        $error = [];
        $module = '';

        foreach (static::$stored as $modules => $url) {

            $segment = Uri::segment(1);
            $storage = self::routes();
            $moduleStore = self::modules();

            if (!array_key_exists($uri, $moduleStore[$modules])) {

                if (array_key_exists($segment, $storage)) {


                    $store = $storage[$segment];
                    foreach ($store as $methods) {
                        $module = $methods['module'];
                    }
                } else {

                    foreach ($storage as $ignored) {

                        $first = $storage[''];
                        $module = $first[$method]['module'];
                    }
                }

                $error = Router::$status = [
                    'http' => 404,
                    'module' => $module,
                    'controller' => 'ErrorController',
                    'action' => 'page404'
                ];
                continue;
            }

            $stored = $moduleStore[$modules][$uri][$method];

            $routes = Router::$status = [
                'http' =>  200,
                'module' => $modules,
                'controller' => $stored['controller'],
                'action' => $stored['action']
            ];

        }


        $response = $routes ?? $error;


        ConditionModule::setCodeStatus($response['http']);

        /**
         * Вернуть пути по методу и ЮРЛ
         */
        return $response;
    }

    /**
     * Получение всех путей
     *
     * @return array
     */
    public static function routes(): array
    {
        $routes = [];

        foreach (self::stored() as $storage) {
            foreach ($storage['route'] as $route) {
                foreach ($route as $key => $value) {
                    $routes[$key] = $value;
                }
            }
        }

        return $routes;
    }

    /**
     * Получение всех путей по модулям
     *
     * @return array
     */
    public static function modules(): array
    {
        $routes = [];

        foreach (self::stored() as $module => $storage) {
            foreach ($storage['route'] as $route) {
                foreach ($route as $key => $value) {
                    $routes[$module][$key] = $value;
                }
            }
        }

        return $routes;
    }
    /**
     * Удалить сохраненный маршрут.
     *
     * @param string $method - метод маршрута.
     * @param string $uri - URI маршрута.
     * @return bool
     */
    public static function remove(string $method, string $uri): bool
    {
        if (isset(static::$stored[$method][$uri])) {
            unset(static::$stored[$method][$uri]);
            return true;
        }

        return false;
    }
}