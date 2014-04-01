<?php

namespace Rawebone\Injector;

/**
 * Resolvers provide a way of returning services/dependencies
 * to the Injector. This allows us to customise the way we
 * return services as required.
 */
interface ResolverInterface
{
    /**
     * Returns the service by name.
     *
     * @param string $service
     * @return \Rawebone\Injector\Func
     * @throws \Rawebone\Injector\ResolutionException
     */
    function resolve($service);
}
