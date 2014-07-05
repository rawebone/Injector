# Injector

Injector provides a low level service injection system partly influenced by
that found in [AngularJS](https://angularjs.org). The idea is somewhat
similar to dependency injection although it actually injects services -
the difference between the two is nuanced but loosely: DI works based on
types, overriding on a per class definition whereas services work on names.

For example, in a DI scenario these two log instances will be the same
(without some other configuration):

```php

use Psr\Log\LoggerInterface;

interface Injectable
{
    function __construct(LoggerInterface $logA, LoggerInterface $logB);
}

```

While DI and configuration of this kind is useful in very large applications
for smaller more concise applications the cost of implementation is higher
as it is normally more complex. As such in these smaller applications, SL
can normally wield more of a benefit as you still get lazy loading of instances
and the ability to easily change a definition.

However traditional SL systems have a drawback in that they work on a pull
system:

```php

$container["logA"] = function ($container) { return new MyLogger($container["fileA"]); };
$container["logB"] = function ($container) { return new MyOtherLogger($container["fileB"]); };

$container["logB"]->warning("blah");

```

As such your application, in addition to some  syntax and bloat from the DSL,
will also not be as succinct (in my humble opinion).

This library provides a low level mechanism for injecting services into instances
to clear some of the bloat, in addition to some low level utilities for working
with functions and function signatures.

## The Injector Approach

```php

use Rawebone\Injector\Injector;

$injector = new Injector();

// By default, the injector will resolve to any callable
// with the name given as a broad brush approach:
function my_service()
{
    return new stdClass();
}

// Injection can be handled automatically by passing through
// a callable to the library

$injector->inject(function ($my_service)
{
    var_dump($my_service); // stdClass
});

// Injection can be handled manually by returning the service by name:

$my_service = $injector->service("my_service");

// Or simply getting arguments for the callable:

$args = $injector->argsFor(function ($my_service) {});
var_dump($args); // array("my_service" => stdClass);


```

As stated, the "resolve anything callable to a service name" is a broad brush
tactic but it doesn't give us what we normally want. As such implementers can
specify their own behaviour by implementing the `Rawebone\Injector\ResolverInterface`
which takes in a service name and returns a value to be injected.

The library also provides a more common solution to the problem through
the `Rawebone\Injector\RegisterResolver`:

```php
<?php

$resolver = new Rawebone\Injector\RegisterResolver();
$resolver->register("serviceA", function () { return new MyService(); });
$resolver->register("serviceB", new \stdClass());

$resolver->registerMany(array(
    "serviceC" => new \stdClass(),
    "serviceD" => function () { return new MyService(); }
));

$injector->resolver($resolver);

```


## License

MIT, please see the [included document](LICENSE) for details.
