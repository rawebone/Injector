<?php

namespace Rawebone\Injector;

/**
 * Represents the information collected by the SignatureReader.
 * This allows for type hinting and, on newer PHP versions with
 * more optimised object handling, better memory usage.
 */
class SignatureData
{
    public $name;
    public $type;
    public $default;
    public $hasDefault;
}
