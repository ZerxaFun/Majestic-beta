<?php

namespace Modules\Backend\Controller;

use Controller;

/**
 * Class BackendController
 * @module Backend
 * @package Modules\Frontend\Controller
 */
class ErrorController extends BackendController
{

    public function page404()
    {
        echo 'Контента нет, 404';
    }
}
