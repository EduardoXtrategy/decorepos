<?php

namespace Sensei\SortingPro\Model\Source;

class State implements \Magento\Framework\Option\ArrayInterface
{
    private $options;

    private $statusCollectionFactory;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory
    ) {
        $this->statusCollectionFactory = $statusCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            /** @var \Magento\Sales\Model\Order\Status[] $statusItems */
            $statusItems = $this->statusCollectionFactory->create()->getItems();

            foreach ($statusItems as $status) {
                $this->options[] = ['value' => $status->getStatus(), 'label' => $status->getLabel()];
            }
        }

        return $this->options;
    }
}
