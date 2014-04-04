<?php

namespace Rawebone\Injector;

/**
 * Provides a naive attempt at resolution to a class or function.
 *
 * By naive, we attempt the following:
 *
 * - A name-to-service function mapping (i.e. "service" = function service() {})
 * - A uppercase first letter name-to-service (i.e. "service" = function Service() {})
 *
 * Uses the Func abstraction so any valid functions can be returned as long as the
 * mapping above is achievable.
 */
class DefaultResolver implements ResolverInterface
{
    protected $resolved = array();

    /**
     * Returns the service by name.
     *
     * @param string $service
     * @return \Rawebone\Injector\Func
     * @throws \Rawebone\Injector\ResolutionException
     */
    public function resolve($service)
    {
        if (isset($this->resolved[$service])) {
            return $this->resolved[$service];
        }

        return $this->resolved[$service] = $this->getFunc($service);
    }

    protected function getFunc($service)
    {
        $names = array($service, ucfirst($service));

        foreach ($names as $name) {
            try {
                return new Func($name);

            } catch (\ErrorException $ex) {}
        }

        throw new ResolutionException("Could not resolve service '$service'");
    }
}
