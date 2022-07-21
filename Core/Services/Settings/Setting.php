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


namespace Core\Services\Settings;


use Exception;


/**
 * Class SettingModel
 * @package Run\Settings
 */
class Setting
{
    /**
     * Получение
     *
     * @param string $key
     * @param string $section
     * @return mixed
     * @throws Exception
     */
    public static function item(string $key, string $section = 'general'): mixed
    {
        if (!Repository::retrieve($section, $key)) {
            self::get($section);
        }

        return Repository::retrieve($section, $key);
    }

    /**
     * Получения значения по ключу и секции
     *
     * @param string $key
     * @param string $section
     * @return string
     * @throws Exception
     */
    public static function value(string $key, string $section = 'general'): string
    {
        $item = static::item(key: $key, section: $section);

        return $item->value ?? '';
    }
}
