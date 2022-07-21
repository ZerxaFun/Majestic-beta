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


namespace Core\Services\Http;


use Core\Services\Routing\ModuleStatus;
use Core\Services\Routing\Response\ConditionModule;

/**
 * Класс для работы с заголовком браузера
 *
 * Class Header
 * @package Core\Services\Http
 */
class Header extends AbstractHeader
{
    /**
     * Обработка отправки запроса Content Type страницы
     * $header принимает string, но в случаи пустого
     * поля в router.php приходит NULL, из-за
     * этого необходимо проводить проверку, пустое
     * ли поле заголовка страницы
     *
     * @param string $header - заголовок страницы
     */
    public static function header (string $header = ''): void
    {
        if(array_key_exists($header, self::$type) === false) {
            $header = 'html';
        }

        self::construct(self::$type[$header]);
    }

    /**
     * Отправка уже обработанного Content-Type
     * конченному клиенту
     *
     * @param string $header    - заголовок страницы, HTML, json и так далее
     */
    private static function construct(string $header): void
    {
        header('Content-Type: ' . $header . ' . ' . self::$charset);
    }


    public static function code(): void
    {
        $code = ConditionModule::getCodeStatus();

        match ($code) {
            200     => self::code200(),
            204     => self::code204(),
            404     => self::code404(),
        };

    }

    public static function code404(): void
    {
        header('HTTP/1.1 404 Not Found');
    }

    public static function code200(): void
    {
        header('HTTP/1.1 200 OK');
    }

    public static function code204(): void
    {
        header('HTTP/1.1 204 NO CONTENT');
    }
}
