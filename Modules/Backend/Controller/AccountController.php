<?php

namespace Modules\Backend\Controller;

use Core\Services\JWT\JWT;
use View;

class AccountController extends BackendController
{
    public static string $layout = 'account/signin';


    final public function signin()
    {

        return View::make('account/signin', self::$data);
    }
}