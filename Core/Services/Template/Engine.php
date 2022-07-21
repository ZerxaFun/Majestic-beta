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

namespace Core\Services\Template;


use Core\Services\Config\Config;
use Core\Services\Container\DI;
use Core\Services\Path\Path;
use Core\Services\Routing\Router;
use ErrorException;
use JsonException;
use RuntimeException;


/**
 * Class Engine
 * @package Core\Services\Template
 */
class Engine
{
    /**
     * @var array - массив модулей и тем, вывод в нем информации о теме и темах.
     */
    public array $themes;

    /**
     * @var array - массив модулей и тем, вывод в нем информации о теме и темах.
     */
    public array $use;

    /**
     * @return string
     */
    public string $themeDir;

    /**
     * @throws JsonException
     * @throws ErrorException
     */
    public function __construct()
    {
        $this->themeDir = Path::theme();
        $this->getThemeModules();
        $this->getThemes();
        $this->isTheme();

        DI::instance()->set('theme', $this);
    }

    /**
     *
     */
    final public function ViewDirectory(): string
    {
        $module = Router::module();
        return sprintf(Path::module('%s' . DIRECTORY_SEPARATOR . 'View'), $module->module);
    }


    private function getThemes(): void
    {
        $this->use = Config::group('theme');
    }

    /**
     * @throws ErrorException
     */
    private function isTheme(): void
    {
        foreach ($this->use as $module => $theme) {

            if(!is_dir(Path::theme($module) . $theme)) {
                throw new ErrorException(
                    sprintf('В файле конфигурации модулю «%s» указана тема «%s», которой не существует.',
                        $module, $theme
                    ));
            }

            $assets =  $this->themes[$module][$theme]['manifest']['assets'];

            $path = Path::theme($module) . $theme . DIRECTORY_SEPARATOR . $assets;
            /**
             * Получение из конфигурации manifest файла с ресурсами
             */
            if(!is_dir($path)) {
                throw new ErrorException(
                    sprintf('В теме «%s» модуля «%s» нет директории ресурсов «%s», которая указана в файле manifest.',
                        $theme, $module, $assets
                    ));
            }

        }
    }

    /**
     * @throws JsonException
     * @throws ErrorException
     */
    private function getThemeModules(): void
    {
        // Load the routes file from each theme that has it.
        foreach (scandir( $this->themeDir) as $module) {
            // Ensure its not a hidden folder.
            if (in_array($module, ['.', '..'], true)) {
                continue;
            }

            foreach (scandir(Path::theme($module)) as $themes) {
                // Ensure its not a hidden folder.
                if (in_array($themes, ['.', '..'], true)) {
                    continue;
                }

                try {
                    $manifest = Path::theme($module) . $themes . DIRECTORY_SEPARATOR . 'manifest.json';

                    if (!is_file($manifest)) {
                        throw new ErrorException(
                            sprintf('В теме «%s» модуля «%s» не найден файл информации «manifest.json»',
                                $themes, $module
                            ));
                    }

                    $theme = json_decode(file_get_contents($manifest), true, 512, JSON_THROW_ON_ERROR);

                    if(!array_key_exists('assets', $theme)) {
                        $theme['assets'] = 'assets';
                    }
                } catch (JsonException $message) {
                    throw new JsonException($message->getMessage());
                }

                $symlink = false;
                $path = Path::public() . $module. DIRECTORY_SEPARATOR;

                $inPath = Path::theme($module) . $themes . DIRECTORY_SEPARATOR . $theme['assets'];

                /**
                 * Смотрим, есть ли symlink на папку
                 */
                if(is_link($path . $themes)) {
                    $symlink = true;
                } else {
                    if(!is_dir($path) && !mkdir($path) && !is_dir($path)) {
                        throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
                    }
                    symlink($inPath, $module . DIRECTORY_SEPARATOR . $themes);
                }


                $this->themes[$module][$themes] = [
                    'path'    => [
                        'module'        => Path::theme($module),
                        'moduleDir'     => Path::module($module),
                        'theme'         => Path::theme($module) . $themes,
                        'resources'     => $inPath,
                        'manifest'      => Path::theme($module) . $themes . DIRECTORY_SEPARATOR . 'manifest.json',
                    ],
                    'manifest'  => $theme,
                    'resources'    => [
                        'in'        =>  $inPath,
                        'path'      =>  $path . $themes,
                        'symlink'   =>  $symlink,
#                        'public'    =>  '//' . $_SERVER['HTTP_HOST'] . '/' . $module . '/' . $themes
                    ]
                ];
            }
        }
    }
}
