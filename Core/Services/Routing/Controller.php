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
use DI;

/**
 * Class Controller
 * @package Core\Services\Routing
 */
class Controller
{
    /**
     * @var string - макет для использования
     */
    public static string $layout = 'layout';

    /**
     * @var array - массив data
     */
    public static array $data = [];

    public static object $module;

    public static array $language;

    public static array $options;

    /**
     * Конструктор контроллера
     * @throws \Exception
     */
    public static function initialize(): void
    {
        /**
         * Информация о подключенном модуле
         */
        $module = new Router();
        $module = $module::module();

        self::$language = [
            'content'      => Language::moduleLanguage(),
            'uri'          => Language::moduleLanguage(),
            'client'       => Client::$language,
        ];



        $moduleTheme = DI::instance()->get('theme')->use[$module->module];


        $module->theme = [
            'module'    => $module->module,
            'name'  => DI::instance()->get('theme')->use[$module->module],
            'public'  => Path::http('base') . $module->module . '/' . $moduleTheme,
        ];

        DI::instance()->set(['module', 'this'], $module);

        self::setData('module', $module);
    }

    /**
     * Массив $this->data для передачи данных из контроллера
     * в View файл
     *
     * @param string $key
     * @param mixed $value
     */
    final public static function setData(string $key, mixed $value): void
    {
        self::$data[$key] = $value;
    }
}
