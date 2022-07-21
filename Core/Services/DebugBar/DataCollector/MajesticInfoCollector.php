<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core\Services\DebugBar\DataCollector;

/**
 * Collects info about PHP
 */
class MajesticInfoCollector extends DataCollector implements Renderable
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'majestic';
    }

    /**
     * @return array
     */
    public function collect()
    {
        return array(
            'version' => 5,
            'interface' => PHP_SAPI
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgets()
    {
        return array(
            "majestic" => array(
                "icon" => "fa-duotone fa-code-merge",
                "tooltip" => "Majestic Version",
                "map" => "majestic.version",
                "default" => ""
            ),
        );
    }
}
