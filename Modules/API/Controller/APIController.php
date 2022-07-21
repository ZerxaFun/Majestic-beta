<?php

namespace Modules\API\Controller;

/**
 * Class BackendController
 * Инициализация модуля, загрузка необходимых данных и их последующее наследование.
 *
 * @module Backend
 * @package Modules\Frontend\Controller
 */
class APIController extends \Core\Services\Routing\APIController
{
    public function home(): \Core\Services\Routing\APIController
    {

         return self::make();
    }
}
