<?php

namespace Rawebone\Injector;

/**
 * Pulls the API together and provides the injection handling.
 */
class Injector
{
    protected $checker;
    protected $reader;
    protected $resolver;
    protected $resolved = array();

    public function __construct()
    {
        $this->checker  = new TypeChecker();
        $this->reader   = new SignatureReader();
        $this->resolver = new DefaultResolver();
    }

    /**
     * Use a custom resolver for returning services.
     *
     * @param ResolverInterface $resolver
     */
    public function resolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Returns the arguments for an injectable.
     *
     * @param mixed $injectable
     * @return array
     */
    public function argsFor($injectable)
    {
        return $this->resolveFuncArgs(new Func($injectable));
    }

    /**
     * Returns the built service by name.
     *
     * @param string $name
     * @return object
     */
    public function service($name)
    {
        if (isset($this->resolved[$name])) {
            return $this->resolved[$name];
        }

        $service = $this->resolver->resolve($name);
        $serviceArgs = $this->resolveFuncArgs($service);

        return $this->resolved[$name] = $service->invoke($serviceArgs);
    }

    /**
     * Gathers the parameters for and runs the injectable.
     *
     * @param $injectable
     */
    public function inject($injectable)
    {
        $func = new Func($injectable);
        $args = $this->resolveFuncArgs($func);

        return $func->invoke($args);
    }

    /**
     * Guts of the operation - binds parameters to services.
     *
     * @param \Rawebone\Injector\Func $func
     * @return array
     */
    protected function resolveFuncArgs(Func $func)
    {
        $params = $this->reader->read($func);

        $args = array();
        foreach ($params as $param) {

            $name = $param["name"];
            $inst = $this->tryService($name);

            if (!$inst && $param["hasDefault"]) {
                $inst = $param["default"];
            } else if ($inst && $param["type"] && !$this->checker->validate($param["type"], $inst)) {
                throw new ResolutionException("Parameter type is not compatible with service '$name'");
            }

            if (!$inst) {
                throw new ResolutionException("Could not resolve parameter '$name'");
            }

            $args[$name] = $inst;
        }

        return $args;
    }

    /**
     * Attempts to resolve the service, else returns null.
     *
     * @param string $name
     * @return null|object
     */
    protected function tryService($name)
    {
        try {
            return $this->service($name);

        } catch (ResolutionException $ex) {
            return null;
        }
    }
}
