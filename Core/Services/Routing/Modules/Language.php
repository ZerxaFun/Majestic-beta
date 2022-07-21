<?php

namespace Core\Services\Routing\Modules;

use Core\Services\Client\Client;
use Core\Services\Http\Uri;
use Core\Services\Path\Path;
use Core\Services\Routing\Router;
use Exception;

/**
 * Получение языка модуля.
 */
class Language
{
    /**
     * Массив модулей с их языками
     * @var array
     */
    public static array $modules = [];

    /**
     * Теги URI языков
     * @var array
     */
    public static array $tag = [];

    /**
     * Все языки модуля
     * @var array
     */
    public static array $languages = [];

    /**
     * @throws Exception
     */
    public static function moduleLanguage(): string
    {
        /**
         * Распарсить URL, если есть совпадения на тег языка в URL, то вывод текущего языка.
         * Если совпадений нет, то вывод языка по умолчанию.
         */
        $module = Router::module()->module;

        $manifest = Manifest::language()['language'][$module];

        if(array_key_exists(Uri::segment(1), $manifest['tag'])) {
            $tag = Uri::segment(1);
        } else {
            $tag = $manifest['default']['module'];
        }

        return $tag;
    }
    /**
     * Возврат текущего модуля
     * @throws Exception
     */
    public static function module(): array
    {
        $manifest = new Manifest();

        $modules = Path::module();

        /**
         * Поочередное чтение Manifest модулей и присвоение им языковых тегов
         */
        foreach (scandir($modules) as $module) {
            if (in_array($module, ['.', '..'], true)) {
                continue;
            }

            self::$modules = $manifest::getModule($module);

            $client = new Client();

            if (array_key_exists('setting', self::$modules) && array_key_exists('language', self::$modules['setting'])) {
                /**
                 * Язык модуля по умолчанию
                 */
                $default = self::$modules['config']['language'];

                /**
                 * Язык клиента по умолчанию
                 */
                $client = $client::$language;

                /**
                 * Все языки модуля
                 */
                self::$languages = self::$modules['setting']['language'];

                /**
                 * Все языки модуля с тегом языка, вместо названия языка
                 */
                $languagesTag = [];

                /**
                 * Содержимое массива модуля языков, содержит в себе язык модуля, язык клиента и финальный язык вывода данных
                 * модуля.
                 */
                $moduleLanguage = [
                    'module'     => self::$languages[$default]['tag'],
                    'client'     => $client,
                ];

                /**
                 * Присвоение переменной $languagesTag тега языка, вместо его полного названия
                 */
                foreach (self::$languages as $value) {
                    $languagesTag[$value['tag']] = $value;
                }

                /**
                 * Конечный язык модуля по следующей логике.
                 * Проверяем модуль на языки и язык клиента. Если язык клиента и язык модуля по умолчанию совпадают,
                 * то выводим именно его.
                 *
                 * Если язык клиента и модуля не совпадают, то пытаемся получить язык клиента из модуля.
                 * Если языка клиента в модуле нет, то выводим язык модуля по умолчанию.
                 */
                if($moduleLanguage['client'] === $moduleLanguage['module']) {
                    $language = $client ?? self::$languages[$default]['tag'];
                } else if(array_key_exists($client, $languagesTag)) {
                    $language = $client ?? $languagesTag[$default['tag']];

                } else {
                    $language = self::$languages[$default]['tag'];
                }

                $moduleLanguage['final'] = $language;

                self::$tag[$module] = [
                    'default'            => $moduleLanguage,
                    'languages'         => self::$languages,
                    'tag'               => $languagesTag,
                ];

            }

        }

        return self::$tag;
    }
}