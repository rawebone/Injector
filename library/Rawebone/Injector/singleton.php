<?php

function injector($serviceOrKill = null)
{
    static $inst;

    if ($serviceOrKill === true) {
        return $inst = null;
    }

    if ($inst === null) {
        $inst = new \Rawebone\Injector\Injector();
    }

    if (is_string($serviceOrKill)) {
        return $inst->service($serviceOrKill);
    }

    return $inst;
}
