<?php

function injector($serviceOrKill = null)
{
    static $inst;

    if ($serviceOrKill === true) {
        return $inst = null;
    }

    if ($inst === null) {
        // Build instance here
    }

    if (is_string($serviceOrKill)) {
        return $inst->service($serviceOrKill);
    }

    return $inst;
}
