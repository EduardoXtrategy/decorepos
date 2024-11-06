<?php

namespace Uzer\Catalogs\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Uzer\Catalogs\Model\Catalog;
use Uzer\Catalogs\Model\ResourceModel\Catalog\CollectionFactory;

class Catalogs extends Template implements BlockInterface
{
    protected $_template = "Uzer_Catalogs::widgets/catalogs.phtml";

    private CollectionFactory $collectionFactory;

    public function __construct(
        Template\Context  $context,
        CollectionFactory $collectionFactory,
        array             $data = []
    )
    {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
    }


    /**
     * @return Catalog[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCatalogs(): array
    {
        $collection = $this->collectionFactory->create();
        return $collection->addFieldToFilter('active', array('eq' => '1'))
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('entity_id', 'desc')
            ->load()
            ->getItems();
    }
}
