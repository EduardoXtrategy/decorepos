<?php

namespace Sensei\SortingPro\Model\Source;

class Methods implements \Magento\Framework\Data\OptionSourceInterface
{

    private $methodProvider;

    public function __construct(
        \Sensei\SortingPro\Model\MethodProvider $methodProvider
    ) {
        $this->methodProvider = $methodProvider;
    }

    public function toOptionArray()
    {
        $options = [];

        foreach ($this->methodProvider->getMethods() as $methodObject) {
            $options[] = [
                'value' => $methodObject->getMethodCode(),
                'label' => $methodObject->getMethodName()
            ];
        }

        return $options;
    }
}
