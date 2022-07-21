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

namespace Core\Services\Routing;

use Core\Services\Http\Header;
use Core\Services\Http\Request;
use Core\Services\Http\Uri;
use Core\Services\Path\Path;
use Core\Services\Template\Engine;
use Core\Services\Template\Layout;
use ErrorException;
use Exception;
use DI;
use Route;
use View;

/**
 * Class Router
 * @package Flexi\Routing
 */
class Router
{

	/**
	 * @var Module - активный модуль.
	 */
	public static Module $module;

    /**
     * @var array - module status
     */
    public static array $status = [];

    /**
     * @var array
     */
    public static array $allModules;


	/**
	 * Возвращение активного модуля
	 *
	 * @return Module
	 */
	public static function module(): Module
	{
		return static::$module;
	}


	/**
	 * Инициализация системы.
	 *
	 * @throws Exception
	 */
	public static function initialize(): void
    {

		# Загрузка роутера
		static::routes();

		# Поиск текущего маршрута
        $route = Repository::retrieve(Request::method(), Uri::segmentString());

        # Создание модуля.
        static::$module = $module = new Module($route);

		# Запуск модуля
		$response = $module->run();

		# Получение ответа, если модуль не API и не требует вывода данных в формате JSON
		if (is_object($response) && method_exists($response, 'respond') && mb_strtolower($module->type) !== 'api') {
                $response->respond();
        }

        /**
         * Получение ответа в формате JSON, если модуль API
         */
        if (mb_strtolower($module->type) === 'api') {
            echo $response->json();
        }

       
        if(mb_strtolower($module->type) === 'module') {
            # Если есть макет для обработки, то подключаем его
            $layout = $module->instance()::$layout;
            # Вывод данных
            if ($layout !== '') {
                echo Layout::get($layout);
            }
        }
	}


    /**
     * Загрузка маршрутизатора приложения
     * @throws ErrorException
     */
    private static function routes(): void
    {

        $modules = Path::module();

        $theme = new Engine();

        // Load the routes file from each module that has it.
        foreach (scandir($modules) as $module) {
            // Ensure its not a hidden folder.
            if (in_array($module, ['.', '..'], true)) {
                continue;
            }

            // Set the module.
            Route::$module = $module;

            // Load file is exists.
            if (is_file($path = $modules . $module . '/routes.php')) {
                require_once $path;
            }

            ## TODO:: Проверка на тип модуля, есть ли у него тема?
            if (($module !== 'Error') && $module !== 'API') {

                if(array_key_exists($module, $theme->use)) {
                    self::$allModules[$module] = [
                        'theme' => [
                            'active' => [
                                'name' => $theme->use[$module],
                                'dir' => $theme->themeDir . $module . DIRECTORY_SEPARATOR . $theme->use[$module]
                            ],
                        ]
                    ];
                } else {
                    throw new ErrorException(
                        sprintf('Модулю «%s» не указана активная тема в файле конфигурации.',
                            $module
                        ));
                }
            }
        }

        DI::instance()->set(['module', 'all'], self::$allModules);

        // Rewrite the application routes.
        static::rewrite();
    }

    /**
     * Переписывает маршруты приложения.
     *
     * @return void
     * @throws Exception
     */
	private static function rewrite(): void
    {
		foreach (Repository::stored() as $method => $routes) {

			foreach ($routes as $uri => $options) {

				$segments   = explode('/', $uri);
				$rewrite    = false;


				foreach ($segments as $key => $segment) {
					$matches = [];

                    /**
                     * Получить сегменты маршрута URI, которые мы должны переписать.
                     */
					preg_match('/\(([0-9a-z]+):([a-z]+)\)/i', $segment, $matches);

                    /**
                     * У нас есть matches?
                     */
					if (!empty($matches)) {

                        /**
                         * Получить реальное значение для этого сегмента и проверить его против правила.
                         */
						$value  = Uri::segment(($key + 1));
						$rule   = $matches[2];
						$valid  = false;


						/**
                         * Сегменты URI, их правила и опции
                         */
						if ($rule === 'int' && is_numeric($value)) {
                            $valid = true;
                        } else if ($rule === 'any') {
                            $valid = true;
                        }

                        /**
                         * Если сегмент дйствителен, то присваиваем ему зачение.
						*/
                        if ($valid === true) {
                            $segments[$key] = $value;
                        }

                        /**
                         * Добавление параметров
                         */
						if (!isset($options['parameters'])) {
                            $options['parameters'] = [$key => $value];
                        } else {
                            $options['parameters'][] = [$key => $value];
                        }

                        /**
                         * Перезапись URL
                         */
						$rewrite = true;
					}

				}

                /**
                 * Если нужно перезаписывать URL
                 */
				if ($rewrite) {
                    /**
                     * Удаление старого URI
                     */
					Repository::remove($method, $uri);

                    /**
                     * Добавление нового URI
                     */
					Repository::store($method, implode('/', $segments), $options);
				}
			}
		}
	}
}
