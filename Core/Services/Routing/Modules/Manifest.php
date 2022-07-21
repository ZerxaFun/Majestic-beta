<?php

namespace Core\Services\Routing\Modules;

use Core\Services\Path\Path;
use Core\Services\Routing\Router;
use JsonException;

class Manifest
{

    /**
     * Название manifest файла
     * @var string
     */
    public static string $name = 'manifest.json';

    /**
     * Рабочий модуль
     * @var string
     */
    public static string $module = '';

    /**
     * Массив manifest
     * @var array
     */
    public static array $manifest = [];

    /**
     * Список всех модулей и Manifest к ним
     * @var array
     */
    public static array $data = [];

    private static function module(): string
    {
        $module = new Router();

        return self::$module = $module::module()->module;
    }


    /**
     * Получение конфигурации Manifest модуля в виде массива PHP
     *
     * @throws JsonException
     */
    public static function load(string $module = ''): array
    {
        if ($module === '') {
            $module = self::module();
        }

        /**
         * Путь к модулю с получением имени текущего модуля
         */
        $path = Path::module($module);

        /**
         * Чтение содержимого файла
         */
        $content = file_get_contents($path . self::$name);

        /**
         * Конвертация JSON в массив PHP
         */
        self::$manifest = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        /**
         * Возврат файла Manifest в виде массива PHP
         */
        return self::$manifest;
    }

    /**
     * Получение Manifest всех модулей
     * @throws JsonException
     */
    public static function modules(): Manifest
    {
        /**
         * Директория всех модулей
         */
        $modules = Path::module();

        /**
         * Цикл для получения всех модулей с пропуском пустых папок
         */
        foreach (scandir($modules) as $module) {
            if (in_array($module, ['.', '..'], true)) {
                continue;
            }

            /**
             * Явное получение конкретного модуля
             */
            $path = Path::module($module);

            /**
             * Чтение содержимого файла
             */
            $content = file_get_contents($path . self::$name);

            /**
             * Запись данных модуля в массив self::$data[ModuleName] = [manifestArrayModule]
             */
            self::$data[$module] = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        }

        return new self();
    }

    /**
     * @throws \Exception
     */
    public static function language()
    {
        $language = new Language();

        self::$data['language'] = $language::module();

        return self::$data;
    }
    /**
     * Получение Manifest конкретного модуля
     * @throws JsonException
     */
    public static function getModule(string $module): array
    {
        $path = Path::module($module);

        /**
         * Чтение содержимого файла
         */
        $content = file_get_contents($path . self::$name);

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}