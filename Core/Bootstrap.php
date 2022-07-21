<?php
declare(strict_types=1);

/**
 *=====================================================
 * Majestic Engine                                    =
 *=====================================================
 * @package Core\Bootstrap                            =
 *-----------------------------------------------------
 * @url http://majestic-studio.com/                   =
 *-----------------------------------------------------
 * @copyright 2021 Majestic Studio                    =
 *=====================================================
 * @author ZerxaFun aKa Zerxa                         =
 *=====================================================
 * @license GPL version 3                             =
 *=====================================================
 *                                                    =
 *                                                    =
 *=====================================================
 */

namespace Core;


use Core\Services\Client\Client;
use Core\Services\Container\DI;
use Core\Services\Database\Database;
use Core\Services\Database\LiteDatabase;
use Core\Services\Environment\Dotenv;
use Core\Services\ErrorHandler\ErrorHandler;
use Core\Services\Http\Uri;
use Core\Services\Orm\Query;
use Core\Services\Routing\Controller;
use Core\Services\Routing\Route;
use Core\Services\Routing\Router;
use Core\Services\Session\Facades\Session;
use Core\Services\Template\Layout;
use Core\Services\Template\Theme\Theme;
use Core\Services\Template\View;
use Exception;


/**
 * Инициализация проекта.
 * 
 * Подключение необходимых альянсов и инициализация модулей ядра.
 */
class Bootstrap
{
    /**
     * @throws Exception
     */
    public static function run(string $pathApplication): void
    {
        /**
         * Установка корневого патча системы.
         */
        DI::instance()->set('baseDir', $pathApplication);

        /**
         * Загрузка классов необходимых для работы
         */
        class_alias(DI::class, 'DI');
        class_alias(Controller::class, 'Controller');
        class_alias(Layout::class, 'Layout');
        class_alias(Route::class, 'Route');
        class_alias(Query::class, 'Query');
        class_alias(View::class, 'View');
        class_alias(Theme::class, 'Theme');

        /**
         * Инициализация сессий.
         */
        Session::initialize();

        /**
         * Инициализация клиента.
         */
        Client::initialize();

        /**
         * Инициализация URI.
         */
        Uri::initialize();

        /**
         * Правильный вывод ошибок
         */
        ErrorHandler::initialize();

        /**
         * Парсинг .env файлов окружения
         */
        Dotenv::initialize();

        /**
         * Подключение к базе данных.
         */
        if($_ENV['database'] === 'laravel') {
            Database::initialize();
        }
        else {
            LiteDatabase::initialize();
        }

        /**
         * Подключение MVC паттерна
         */
        Router::initialize();

        /**
         * Закрытие подключения к базе данных после окончания запроса.
         */
        if($_ENV['database'] !== 'laravel') {
            LiteDatabase::finalize();
        }
    }
}