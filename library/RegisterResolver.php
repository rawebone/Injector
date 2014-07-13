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

		return new Func($this->services[$service]);
	}

	/**
	 * Registers a service by name into the resolver.
	 *
	 * @param string $service
	 * @param callable $callable
	 * @throws ResolutionException
	 */
	public function register($service, $callable)
    {
		if (!is_callable($callable)) {
			throw new ResolutionException("Service '$service' cannot be register as it's value is invalid");
		}

		$this->services[$service] = $callable;
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

	/**
	 * Registers an object with the container.
	 *
	 * @param string $service
	 * @param object $object
	 * @see register
	 */
	public function registerObject($service, $object)
    {
		if (!is_object($object)) {
			throw new ResolutionException("Service '$service' cannot be register as it's value is invalid");
		}

        $this->services[$service] = function () use ($object) { return $object; };
    }
}
