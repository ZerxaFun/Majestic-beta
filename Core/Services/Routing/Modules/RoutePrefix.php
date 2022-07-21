<?php

namespace Core\Services\Routing\Modules;

use Core\Services\Config\Config;
use Core\Services\Http\Uri;
use Core\Services\Routing\Repository;
use Core\Services\Routing\Router;
use Exception;

/**
 * Класс для добавления префикса языка и создания уникальной страницы для каждого языка
 *
 * Пример:
 * Присутствуют два языка, en и ru.
 * ru является основным языком системы, вывод контента без префикса.
 * EN в свою очередь является второстепенным языком и весь контент с языком en выводится
 * с префиксом /en/ в URL адресе сайта.
 *
 * Это позволит разделить контент для разных языков.
 */
class RoutePrefix
{
    /**
     * Получение текущего языка модуля и присвоение ему тега
     *
     * @return array
     * @throws Exception
     */
    public static function languageTag(): array
    {
        $lang = Language::module();

        $systemLang = Config::item('defaultLanguage');


        $result = [];

        foreach ($lang as $module => $value) {

            $value = $value['default'];
            $real = $value['module'];


            if($systemLang === $value['module']) {
                $real = '';
            }

            $result[$module] = [
                'lang'  => $value['module'],
                'prefix'    => $value['module'],
                'realPrefix'    => $real
            ];

        }


        return $result;
    }
}