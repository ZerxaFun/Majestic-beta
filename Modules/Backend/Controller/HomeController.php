<?php

namespace Modules\Backend\Controller;

use View;

class HomeController extends BackendController
{

    public static function home(): View
    {
        return View::make('dashboard', self::$data);
    }
}