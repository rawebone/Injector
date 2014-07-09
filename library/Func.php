<?php

namespace Rawebone\Injector;

/**
 * Func represents any callable function in the PHP language, allowing for
 * reflection and invocation. This allows the Injector to have the maximum
 * range when injecting services.
 *
 * Our strategy here is to be as lazy as possible about what type of subject
 * we have until we absolutely need it, preventing some fairly weighty calls
 * to is_* functions. Even when we do, to prevent having to do more work again,
 * we break down the reflection generation and function invocation handling
 * into their own methods which are then dynamically referenced. Still, we
 * only do this when absolutely necessary to ensure the most efficient performance.
 */
class Func
{
    protected $subject;
    protected $reflection;
	protected $reflectionFunction;
	protected $invokeFunction;
	protected $type;

    public function __construct($subject)
    {
        $this->subject = $subject;

		// This check helps to cover off most cases quickly
		if (!is_callable($subject, true)) {
			throw new \ErrorException("Subject is not a valid callable");
		}
    }

    public function reflection()
    {
		$this->lazyInit();

        if (!$this->reflection) {
			$func = $this->reflectionFunction;
			$this->reflection = $this->$func();
		}

		return $this->reflection;
    }

    public function invoke(array $args)
    {
        $this->lazyInit();

		$func = $this->invokeFunction;
		return $this->$func($args);
    }

	public function type()
	{
		if ($this->type) {
			return $this->type;
		}

		return $this->type = CallableType::create()->type($this->subject);
	}

	protected function lazyInit()
	{
		if ($this->reflectionFunction) {
			return;
		}

		switch ($this->type()) {
			case CallableType::TYPE_FUNCTION:
				$this->reflectionFunction = "reflectionForFunction";
				$this->invokeFunction = "invokeForFunction";
				break;

			case CallableType::TYPE_ARRAY:
				$this->reflectionFunction = "reflectionForArray";
				$this->invokeFunction = "invokeForArray";
				break;

			case CallableType::TYPE_INVOKABLE:
				$this->reflectionFunction = "reflectionForInvokable";
				$this->invokeFunction = "invokeForInvokable";
				break;

			case CallableType::TYPE_CONSTRUCTABLE:
				$this->reflectionFunction = "reflectionForConstructable";
				$this->invokeFunction = "invokeForConstructable";
				break;

			default:
				throw new \ErrorException("Subject is not a valid callable");
				break;
		}
	}

	protected function reflectionForFunction()
	{
		return new \ReflectionFunction($this->subject);
	}

	protected function reflectionForInvokable()
	{
		return new \ReflectionMethod($this->subject, "__invoke");
	}

	protected function reflectionForArray()
	{
		return new \ReflectionMethod($this->subject[0], $this->subject[1]);
	}

	protected function reflectionForConstructable()
	{
		return new \ReflectionMethod($this->subject, "__construct");
	}

	protected function invokeForFunction($args)
	{
		return $this->reflection()->invokeArgs($args);
	}

	protected function invokeForInvokable($args)
	{
		return $this->reflection()->invokeArgs($this->subject, $args);
	}

	protected function invokeForArray($args)
	{
		return $this->reflection()->invokeArgs($this->subject[0], $args);
	}

	protected function invokeForConstructable($args)
	{
		return $this->reflection()->getDeclaringClass()->newInstanceArgs($args);
	}
}
