<?php

namespace Modules\Error\Controller;

use Controller;
use Core\Services\Http\Header;
use View;

/**
 * Class ErrorController
 * @module Error
 * @package Modules\Frontend\Controller
 */

class ErrorController extends Controller
{
	# Шаблон страниц
	public string $layout = 'error';

    /**
     * Core error 500
     *
     * @action  page500
     * @url     none
     * @return View
     */
    public function page500(): View
    {
        $this->layout = '500';

        /**
         * Передача View данных контроллера.
         */
        return View::make('500', $this->data);
    }
}
