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


namespace Core\Services\Database;


use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

/**
 * Class Database
 */
class Database
{
    /**
     * Инициализация коннекта к базе данных.
     */
    public static function initialize(): void
    {
        $capsule = new Capsule;




        $capsule->addConnection([
            'driver'   => 'mongodb',
            'host'     => $_ENV['db_host'],
            'port'     => '27017',
            'username' => '',
            'password' => '',
            'database' => 'database',
            'options' => [
                'db' => 'PUT_THERE_DB_NAME' //Sets the auth DB
            ]], 'mdb');

        $capsule->addConnection([
            'driver' => $_ENV['db_driver'],
            'host' => $_ENV['db_host'],
            'database' => $_ENV['db_name'],
            'username' => $_ENV['db_username'],
            'password' => $_ENV['db_password'],
            'charset' => $_ENV['db_charset'],
            'collation' => $_ENV['db_collation'],
            'prefix' => '',
        ], 'default');

        $capsule->setEventDispatcher(new Dispatcher(new Container));

        $capsule->setAsGlobal();

        $capsule->bootEloquent();

    }
}
