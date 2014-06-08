<?php

namespace Rawebone\Injector;

/**
 * Func represents any callable function in the PHP language, allowing for
 * reflection and invokation. This allows the Injector to have the maximum
 * range when injecting services.
 */
class Func
{
    protected $subject;

    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    public function reflection()
    {
        if ($this->isFunction()) {
            return new \ReflectionFunction($this->subject);
        } else if ($this->isInvokable()) {
            return new \ReflectionMethod($this->subject, "__invoke");
        } else if ($this->isArrayCallback()) {
            return new \ReflectionMethod($this->subject[0], $this->subject[1]);
        } else if ($this->isConstructable()) {
            return new \ReflectionMethod($this->subject, "__construct");
        } else {
            throw new \ErrorException("Could not get a reflection for invalid function");
        }
    }

    public function invoke(array $args)
    {
        if ($this->isFunction()) {
            return $this->reflection()->invokeArgs($args);
        } else if ($this->isInvokable()) {
            return $this->reflection()->invokeArgs($this->subject, $args);
        } else if ($this->isArrayCallback()) {
            return $this->reflection()->invokeArgs($this->subject[0], $args);
        } else if ($this->isConstructable()) {
            return $this->reflection()->getDeclaringClass()->newInstanceArgs($args);
        } else {
            throw new \ErrorException("Could not get a reflection for invalid function");
        }
    }

    protected function isFunction()
    {
        return ($this->subject instanceof \Closure
                || is_string($this->subject) && function_exists($this->subject));
    }

    protected function isInvokable()
    {
        return is_object($this->subject) && method_exists($this->subject, "__invoke");
    }

    protected function isArrayCallback()
    {
        return is_array($this->subject) && is_callable($this->subject);
    }

    protected function isConstructable()
    {
        return is_string($this->subject) && class_exists($this->subject) && method_exists($this->subject, "__construct");
    }
}
