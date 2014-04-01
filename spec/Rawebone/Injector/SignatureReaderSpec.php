<?php

namespace spec\Rawebone\Injector;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rawebone\Injector\Func;

class SignatureReaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Injector\SignatureReader');
    }

    function it_should_read_parameters_from_reflection()
    {
        $this->read(new Func(__NAMESPACE__ . '\test_read_a'))
             ->shouldReturn(array(
                    array(
                        "name" => "abc",
                        "type" => "array",
                        "default" => null,
                        "hasDefault" => true
                    )
              ));

        $this->read(new Func(__NAMESPACE__ . '\test_read_b'))
             ->shouldReturn(array(
                    array(
                        "name" => "abc",
                        "type" => "stdClass",
                        "default" => "",
                        "hasDefault" => false
                    )
             ));
    }
}

function test_read_a(array $abc = null)
{
}

function test_read_b(\stdClass $abc)
{
}
