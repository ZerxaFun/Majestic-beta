<?php
declare(strict_types=1);

use Core\Bootstrap;
use Core\Services\DebugBar\StandardDebugBar;
use Core\Services\ErrorHandler\ErrorHandler;
use Core\Services\Template\Theme\i18n;

/**
 *=====================================================
 * Majestic Next Engine - by Zerxa Fun                =
 *-----------------------------------------------------
 * @url: http://majestic-studio.com/                  =
 *-----------------------------------------------------
 * @copyright: 2021 Majestic Studio and ZerxaFun      =
 *=====================================================
 * @license GPL version 3                             =
 *=====================================================
 * index.php - исполняемый файл и точка входа         =
 * в систему.                                         =
 * Подключение composer и констант фреймворка         =
 *=====================================================
 */

require '../vendor/autoload.php';

    $debugbar = new StandardDebugBar();
    $debugbarRenderer = $debugbar->getJavascriptRenderer();

    Bootstrap::run(dirname(__DIR__));
i18n::theme();
    dd( $debugbarRenderer->render());

