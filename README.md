# Injector

Injector provides a low level service injection system partly influenced by
that found in [AngularJS](https://angularjs.org). The idea is somewhat
similar to dependency injection, although it actually injects services.
Consider an example with [Pimple](https://github.com/fabpot/pimple):

```php
<?php

$container = new Pimple();
$container["service"] = function () { return new \stdClass(); };
$container["consumer"] = function () use ($container) { return new myService($container["service"]); };

$container["consumer"]->blah();

```

So, we have service location but we have to do all of the configuration ourselves.
While this isn't so bad it gets more tricky when we want to do something like:

```php
<?php

$func = function (myService $service)
{
    return $service->blah();
};

```

How do we know what services are required? You can use a full DI solution for
this, however that also requires a lot of configuration either with meta data
or manual coding like in the first example to specify what constitutes a
dependency.

Then there are other problems - you have to really tie into a solution to get
any benefit, some systems require major architectural underpinnings etc.

So what this library provides is an alternate solution. It provides an API
for working with functions as units that can be injected into and provides
a linker to match service names, based on the parameter names, to enable
injection or retrieval for higher level processing.

As such, we can rewrite the above code examples as:

```php

function service()
{
    return new \stdClass();
}

function consumer(\stdClass $service)
{
    return new myService($service);
}

$func = function (myService $consumer)
{
    $consumer->blah();
};

echo injector()->inject($func), PHP_EOL; // The result of blah

```

This is, too my mind, a much more natural way of working. But we are
not limited to just injecting the dependencies - they can be returned
by a call to `argsFor($func)` - or we can resolve the service we want
by `injector("service")`. As such, this can provide the foundation
for higher level functionality or be used. Resolution of services
can also be customised to your applications requirements by specifying
a `Resolver`.

Resolvers take a single parameter - the service name - and should return
a `Func` object. For example you could define a custom Resolver for your
project like:

```php
<?php

class MyResolver implements \Rawebone\Injector\ResolverInterface
{
    public function resolve($name)
    {
        $method = "getService" . ucfirst($name);
        if (!method_exists($this, $method)) {
            throw new \Rawebone\Injector\ResolutionException($name);
        }

        return new \Rawebone\Injector\Func(array($this, $method));
    }

    public function getServiceService()
    {
        return new \stdClass();
    }

    public function getServiceConsumer(\stdClass $service)
    {
        return new myService($service);
    }
}

injector()->resolve(new MyResolver());

```

And the earlier example will work in exactly the same way.

