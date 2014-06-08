<?php

namespace Rawebone\Injector;

/**
 * Validates that a type can be passed as an argument to a Func.
 */
class TypeChecker
{
    public function validate($expected, $actual)
    {
        if ($expected === "array") {
            return is_array($actual);
        }

        return ($actual instanceof $expected);
    }
}
