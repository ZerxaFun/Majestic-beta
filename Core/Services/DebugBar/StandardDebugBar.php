<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core\Services\DebugBar;

use Core\Services\DebugBar\DataCollector\ExceptionsCollector;
use Core\Services\DebugBar\DataCollector\LoadingCollector;
use Core\Services\DebugBar\DataCollector\MajesticInfoCollector;
use Core\Services\DebugBar\DataCollector\MemoryCollector;
use Core\Services\DebugBar\DataCollector\MessagesCollector;
use Core\Services\DebugBar\DataCollector\PhpInfoCollector;
use Core\Services\DebugBar\DataCollector\RequestDataCollector;
use Core\Services\DebugBar\DataCollector\TimeDataCollector;

/**
 * Debug bar subclass which adds all included collectors
 */
class StandardDebugBar extends DebugBar
{
    /**
     * @throws DebugBarException
     */
    public function __construct()
    {
        $this->addCollector(new PhpInfoCollector());
        $this->addCollector(new MessagesCollector());
        $this->addCollector(new RequestDataCollector());
        $this->addCollector(new MemoryCollector());
        $this->addCollector(new ExceptionsCollector());
        $this->addCollector(new MajesticInfoCollector());
        $this->addCollector(new LoadingCollector());
        $this->addCollector(new TimeDataCollector());
    }
}
