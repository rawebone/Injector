<?php

namespace Rawebone\Injector;

class RegisterResolver implements ResolverInterface
{
	protected $services = array();

	/**
	 * Returns the service by name.
	 *
	 * @param string $service
	 * @return \Rawebone\Injector\Func
	 * @throws \Rawebone\Injector\ResolutionException
	 */
	public function resolve($service)
	{
		if (!isset($this->services[$service])) {
			throw new ResolutionException("Could not resolve service '$service'");
		}

		return $this->services[$service];
	}

	/**
	 * Registers a service by name into the resolver. $callable
	 * can be an object instance, in which case it will be wrapped.
	 *
	 * @param string $service
	 * @param callable|object $callable
	 * @throws ResolutionException
	 */
	public function register($service, $callable)
    {
		$realCallable = null;

		if (!is_callable($callable) && is_object($callable)) {
			$realCallable = function () use ($callable) { return $callable; };
		} else if (is_callable($callable)) {
			$realCallable = $callable;
		} else {
			throw new ResolutionException("Service '$service' cannot be register as it's value is invalid");
		}

        $this->services[$service] = new Func($realCallable);
    }

	/**
	 * Convenience wrapper over register().
	 *
	 * @param array $services
	 * @see register
	 */
	public function registerMany(array $services)
    {
        foreach ($services as $service => $callable) {
			$this->register($service, $callable);
		}
    }
}
