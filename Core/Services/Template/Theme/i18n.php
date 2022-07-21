<?php

namespace Core\Services\Template\Theme;

use Core\Services\Container\DI;
use Core\Services\Path\Path;

class i18n
{
    public static string $module;
    public static string $theme;

    public static bool $language = false;
    public static array $languages;
    private static string $languageDir = 'language';


    public static function theme(): void
    {
        /**
         * Получение активного модуля и его темы для поиска в нем папки self::$languageDir
         */
        self::$module = DI::instance()->get('module')['this']->theme['module'];
        self::$theme  = DI::instance()->get('module')['this']->theme['name'];


        /**
         * Путь к активной теме текущего модуля
         */
        $path = Path::theme(self::$module . DIRECTORY_SEPARATOR . self::$theme);

        /**
         * Проверка, есть ли у темы файлы локализации
         */
        if(is_dir($path . DIRECTORY_SEPARATOR . self::$languageDir)) {
            self::$language = true;
        }


        if(self::$language === true) {
            foreach (scandir($path  . DIRECTORY_SEPARATOR . self::$languageDir) as $languagesDir) {
                if (in_array($languagesDir, ['.', '..'], true)) {
                    continue;
                }
                self::$languages[$languagesDir] = $path . self::$languageDir . DIRECTORY_SEPARATOR . $languagesDir;
            }
        }
    }



}