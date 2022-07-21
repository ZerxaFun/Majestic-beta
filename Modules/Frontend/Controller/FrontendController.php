<?php

namespace Modules\Frontend\Controller;

use Controller;
use View;

/**
 * Class BackendController
 * @module Backend
 * @package Modules\Frontend\Controller
 */
class FrontendController extends Controller
{


    public function home(): View
    {

        return View::make('layout', self::$data);
    }
}
