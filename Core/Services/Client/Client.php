<?php
/**
 *=====================================================
 * Majestic Engine - by Zerxa Fun (Majestic Studio)   =
 *-----------------------------------------------------
 * @url: http://majestic-studio.ru/                   -
 *-----------------------------------------------------
 * @copyright: 2022 Majestic Studio and ZerxaFun      -
 *=====================================================
 *                                                    =
 *                                                    =
 *                                                    =
 *=====================================================
 * Made in Ukraine, with Love.
 */

namespace Core\Services\Client;


use Core\Services\Config\Config;
use Core\Services\Routing\Controller;
use Core\Services\Session\Facades\Session;
use DI;

class Client
{
    /**
     * @var array[]
     */
    public static array $data;

    public static string $language;

    public static function initialize(): void
    {
        self::setLanguage();
        self::$language = self::$data['language']['session'];

        DI::instance()->set('client', self::$data);
    }

    public static function setLanguage(): void
    {
        /**
         * Если язык в сессии не установлен, то добавляем язык по умолчанию в приоритет сессии
         */
        if(Session::get('lang') === false) {
            Session::put('lang', Config::item('defaultLanguage'));
        }

        /**
         * Данные клиента
         */
        self::$data = [
            'language'      => [
                'browser'   => Language::getBrowserLang(),
                'session'   => self::getSessionLanguage(),
                'default'   => Config::item('defaultLanguage'),
            ]
        ];
    }

    public static function getSessionLanguage(): string
    {
        $session = Session::get('lang');

        if($session === false) {
            return Config::item('defaultLanguage');
        }

        return $session;
    }
}
