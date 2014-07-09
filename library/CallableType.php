<?php

namespace Rawebone\Injector;

/**
 * CallableType provides an easy interface for identifying what type
 * of callable a subject is. This logic was initially in the Func object
 * but there are performance and practical reasons why this is not effective.
 */
class CallableType
{
	const TYPE_NONE = 0;
	const TYPE_FUNCTION = 1;
	const TYPE_INVOKABLE = 2;
	const TYPE_ARRAY = 4;
	const TYPE_CONSTRUCTABLE = 8;
	const TYPE_UNKNOWN = 16;

	public function type($subject)
	{
		if (is_string($subject) && class_exists($subject) && method_exists($subject, "__construct")) {
			return self::TYPE_CONSTRUCTABLE;

		} else if (!is_callable($subject)) {
			return self::TYPE_NONE;

		} else if ($subject instanceof \Closure || is_string($subject) && function_exists($subject)) {
			return self::TYPE_FUNCTION;

		} else if (is_array($subject)) {
			return self::TYPE_ARRAY;

		} else if (is_object($subject) && method_exists($subject, "__invoke")) {
			return self::TYPE_INVOKABLE;

		} else {
			return self::TYPE_UNKNOWN;
		}
	}

	public static function create()
	{
		return new static();
	}
}
