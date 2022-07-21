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
use Core\Services\Path\Path;
use Core\Services\Routing\Modules\Language;
use Core\Services\Routing\Modules\Manifest;
use DI;
use Exception;
use RuntimeException;

/**
 * Class Module
 * @package Core\Services\Routing
 */
class Module
{
    public static array $language;
    /**
     * @var Controller - контроллер.
     */
    public Controller $instance;

    /**
     * @var APIController - контроллер.
     */
    public APIController $APIInstance;

    /**
     * Тип модуля, API, module
     * @var string
     */
    public string $type;

    /**
     * @var mixed - ответ действий.
     */
    public mixed $response;

    /**
     * @var string - активный модуль.
     */
    public string $module = '';

    public string $http = '';

    public array $manifest = [];
    /**
     * @var string - активный контроллер.
     */
    public string $controller = '';

    /**
     * @var string - активное действие.
     */
    public string $action = '';

    /**
     * @var array - активные параметры.
     */
    public array $parameters = [];

    /**
     * @var string[]
     */
    public array $theme;

    /**
     * Конструктор
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {

        foreach ($config as $key => $value) {
            $this->$key = $value;
        }

    }

    /**
     * Возвращает экземпляр контроллера.
     *
     * @return Controller
     */
    public function instance(): Controller
    {
        return $this->instance;
    }

    /**
     * Запускает активное действие контроллера.
     *
     * @throws Exception
     */
    final public function run()
    {

        if($this->module === '') {
            throw new RuntimeException('Clean module. Not modules for this route');
        }


        $this->manifest = Manifest::load();

        /**
         * Построение имени класса
         */
        $class = '\\Modules\\' . $this->module . '\Controller\\' . $this->controller;

        $this->type = $this->manifest['type'];

        Controller::initialize();

        if (class_exists($class)) {
            if($this->type === 'API') {
                $this->APIInstance = new $class;
                $this->response = call_user_func_array([$this->APIInstance, $this->action], $this->parameters);
            } else {
                $this->instance = new $class;
                $this->response = call_user_func_array([$this->instance, $this->action], $this->parameters);
            }


            /**
             * Возвращение ответа.
             */
            return $this->response;
        }

        throw new RuntimeException(
            sprintf('Контроллер <strong>%s</strong> не найден.', $class)
        );
    }
}
