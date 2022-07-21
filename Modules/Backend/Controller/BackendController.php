<?php

namespace Modules\Backend\Controller;

use Controller;
use Core\Services\Auth\Auth;
use Core\Services\Http\Redirect;
use Core\Services\Http\Uri;
use Core\Services\Routing\Modules\Language;
use JetBrains\PhpStorm\NoReturn;

/**
 * Class BackendController
 * Инициализация модуля, загрузка необходимых данных и их последующее наследование.
 *
 * @module Backend
 * @package Modules\Frontend\Controller
 */
class BackendController extends Controller
{
    /**
     * Указание View заглавного шаблона модуля
     * @var string View::layout();
     */
    public static string $layout = 'main';

    /**
     * Подключение функций и загрузка данных модуля функцией self::load();
     */

    public function __construct()
    {
        /**
         * Проверка авторизации администратора.
         */
        self::authentication();

    }

    /**
     * Проверка авторизации пользователя.
     * Если пользователь не авторизирован, то проверяем, не находится ли он на странице авторизации.
     * Если пользователь не находится на странице авторизации, то отправляем его на неё.
     *
     * @return void Redirect::go() : false;
     */
    final public static function authentication(): void
    {
        $signUrl = '/admin/account/signin';

        if(!Auth::authorized() && '/' . Uri::segmentString() !== $signUrl) {
            Redirect::go($signUrl);
        }
    }

}
