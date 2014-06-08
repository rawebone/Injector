<?php

namespace Rawebone\Injector;

class SignatureReader
{
    public function read(Func $function)
    {
        $params = array();

        foreach ($function->reflection()->getParameters() as $param) {
            $params[] = array(
                "name" => $param->getName(),
                "type" => $this->getType($param),
                "default" => $this->getDefault($param),
                "hasDefault" => $param->isOptional()
            );
        }

        return $params;
    }

    protected function getType(\ReflectionParameter $param)
    {
        if ($param->isArray()) {
            return "array";
        }

        if (($cls = $param->getClass())) {
            return $cls->getName();
        }

        return "";
    }

    protected function getDefault(\ReflectionParameter $param)
    {
        return ($param->isOptional() ? $param->getDefaultValue() : "");
    }
}
