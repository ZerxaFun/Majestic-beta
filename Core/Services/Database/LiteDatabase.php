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


use PDO;
use PDOException;
use Exception;
use RuntimeException;


/**
 * Class Database
 * @package Flexi\Database
 */
class LiteDatabase
{

    /**
     * @var mixed|null $connection
     */
    private static mixed $connection;

    /**
     * Получение текущего соединение с базой данных.
     *
     * @return PDO
     */
    public static function connection(): PDO
    {
        return static::$connection;
    }

    /**
     * Инициализация соединения к базе данных.
     *
     * @throws Exception
     */
    public static function initialize(): void
    {
        static::$connection = static::connect();
    }

    /**
     * Финальное подключение к базе данных.
     *
     * @return void
     */
    public static function finalize(): void
    {
        static::$connection = null;
    }

    /**
     * Подключение к базе данных
     *
     * @return null|PDO
     * @throws Exception
     */
    private static function connect(): ?PDO
    {
        /**
         * Получение параметров для подключения к базе данных
         *
         * @var $driver     - Драйвер
         * @var $host       - Имя хоста
         * @var $username   - Имя пользователя базы данных
         * @var $password   - Пароль пользователя базы данных
         * @var $name       - Название базы данных
         * @var $charset    - Кодировка базы данных
         * @var $dsn        - DNS базы данных
         * @var $options    - Опции @PDO
         */

        $driver     = $_ENV['db_driver'];
        $host       = $_ENV['db_host'];
        $username   = $_ENV['db_username'];
        $password   = $_ENV['db_password'];
        $name       = $_ENV['db_name'];
        $charset    = $_ENV['db_charset'];
        $dsn        = sprintf('%s:host=%s;dbname=%s;charset=%s', $driver, $host, $name, $charset);
        $options    = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
        ];

        /**
         * Не работает как нужно, но не подключаемся к базе данных, если значения путы
         */
        if ($driver === '' || $username === '' || $name === '') {
            return null;
        }

        /**
         * Попытка подключения к базе данных
         */
        try {
            $connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $error) {
            throw new RuntimeException($error->getMessage());
        }

        /**
         * Возврат соединение к базе данных в случаи успеха.
         */
        return $connection;
    }

    /**
     * Получение индикатора последней вставленной записи в базу данных
     *
     * @return int
     */
    public static function insertId(): int
    {
        return (int) static::$connection->lastInsertId();
    }
}
