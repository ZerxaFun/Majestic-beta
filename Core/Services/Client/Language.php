<?php

namespace Core\Services\Client;

use Core\Services\Config\Config;
use Core\Services\Session\Facades\Session;


class Language
{
    public static string $language;

    public static function getLanguage(): string
    {
        /*
         * Session driver
         */
        $session = new Session();

        /**
         * Получение разрешенных языков
         */
        $config = Config::group('lang');

        /**
         * Если не установлен язык в сессии, то устанавливаем его
         *
         * Получаем язык браузера, и делаем его языком по умолчанию, если он присутствует
         * в массиве разрешенных языков
         *
         * Если языка нет в списке разрешенных, то устанавливаем язык "по умолчанию".
         */
        if($session::get('lang') === false) {
            self::getBrowserLang();

            if(in_array(self::$language, $config, true) === true) {
                $session::put('lang', self::$language);
            } else {
                $session::put('lang', Config::item('lang'));
            }
        }

        return $session::get('lang');
    }

    /**
     * Получение языка системы клиента
     *
     * @return string
     */
    public static function getBrowserLang(): string
    {
       self::BrowserLang();

       if(self::BrowserLang() === null) {
           self::$language = '';
       }

       return self::$language;
    }

    private static function BrowserLang(): void
    {
        /**
         * Получение языка HTTP заголовка браузера
         */
        if(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
            $user_pref_lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

            foreach($user_pref_lang as $lang) {
                $lang = substr($lang, 0, 2);
                self::$language = $lang;
            }
        }
    }
}
