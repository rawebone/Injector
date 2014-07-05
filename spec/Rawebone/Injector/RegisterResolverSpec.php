<?php

namespace spec\Rawebone\Injector;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RegisterResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Injector\RegisterResolver');
    }

	function it_should_throw_an_exception_if_service_not_registered()
	{
		$this->shouldThrow('Rawebone\Injector\ResolutionException')
			 ->during("resolve", array("serviceA"));
	}

	function it_should_return_a_service_after_registration()
	{
		$this->register("serviceA", function () { });

		$this->resolve("serviceA")->shouldReturnAnInstanceOf('Rawebone\Injector\Func');
	}

	function it_should_return_a_service_after_registration_of_an_object_instance()
	{
		$this->register("serviceA", new \stdClass());

		$this->resolve("serviceA")->shouldReturnAnInstanceOf('Rawebone\Injector\Func');
	}

	function it_should_fail_to_register_if_not_object_or_callable()
	{
		$this->shouldThrow('Rawebone\Injector\ResolutionException')
			 ->during("register", array("serviceA", "a"));
	}

	function it_should_register_many()
	{
		$this->registerMany(array(
			"serviceA" => new \stdClass(),
			"serviceB" => function () { }
		));

		$this->resolve("serviceA")->shouldReturnAnInstanceOf('Rawebone\Injector\Func');
		$this->resolve("serviceB")->shouldReturnAnInstanceOf('Rawebone\Injector\Func');
	}

}
