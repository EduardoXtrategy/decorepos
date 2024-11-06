<?php

declare(strict_types=1);

namespace Sensei\SortingPro\Model\Di;

class Wrapper
{

    private $objectManagerInterface;

    private $name;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManagerInterface,
        $name = ''
    ) {
        $this->objectManagerInterface = $objectManagerInterface;
        $this->name = $name;
    }

    public function __call($name, $arguments)
    {
        $result = false;
        if ($this->name && class_exists($this->name)) {
            $object = $this->objectManagerInterface->get($this->name);
            $result = call_user_func_array([$object, $name], $arguments);
        }

        return $result;
    }
}
