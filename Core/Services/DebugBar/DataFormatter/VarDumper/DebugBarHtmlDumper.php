<?php

namespace Core\Services\DebugBar\DataFormatter\VarDumper;



use Core\Services\DevDumper\Dumper\HtmlDumper;

/**
 * We have to extend the base HtmlDumper class in order to get access to the protected-only
 * getDumpHeader function.
 */
class DebugBarHtmlDumper extends HtmlDumper
{
    /**
     * Resets an HTML header.
     */
    public function resetDumpHeader()
    {
        $this->dumpHeader = null;
    }

    public function getDumpHeaderByDebugBar() {
        // getDumpHeader is protected:
        return str_replace('pre.sf-dump', '.phpdebugbar pre.sf-dump', $this->getDumpHeader());
    }
}
