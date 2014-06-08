<?php

namespace Rawebone\Injector;

class SignatureReader
{
    /**
     * @param Func $function
     * @return SignatureData[]
     */
    public function read(Func $function)
    {
        $params = array();

        foreach ($function->reflection()->getParameters() as $param) {
            $data = new SignatureData();

            $data->name = $param->getName();
            $data->type = $this->getType($param);
            $data->default = $this->getDefault($param);
            $data->hasDefault = $param->isOptional();

            $params[] = $data;
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
