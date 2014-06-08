<?php

namespace spec\Rawebone\Injector;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rawebone\Injector\Func;
use Rawebone\Injector\SignatureData;

class SignatureReaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Injector\SignatureReader');
    }

    function it_should_read_parameters_from_reflection()
    {
        $data = new SignatureData();
        $data->name = "abc";
        $data->type = "stdClass";
        $data->default = "";
        $data->hasDefault = false;

        $this->read(new Func(__NAMESPACE__ . '\test_read_a'))
             ->shouldBeLike(array($data));
    }

    function it_should_read_parameters_from_reflection_with_default()
    {
        $data = new SignatureData();
        $data->name = "abc";
        $data->type = "array";
        $data->default = null;
        $data->hasDefault = true;

        $this->read(new Func(__NAMESPACE__ . '\test_read_b'))
             ->shouldBeLike(array($data));
    }
}

function test_read_b(array $abc = null)
{
}

function test_read_a(\stdClass $abc)
{
}
