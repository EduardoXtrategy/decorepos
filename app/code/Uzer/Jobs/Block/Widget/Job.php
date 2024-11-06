<?php

namespace Uzer\Jobs\Block\Widget;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Uzer\Jobs\Model\ResourceModel\UzerJob\CollectionFactory;
use Uzer\Jobs\Model\UzerJob;

class Job extends Template implements BlockInterface
{
    protected $_template = "Uzer_Jobs::widgets/jobs.phtml";

    private CollectionFactory $collectionFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
    }


    /**
     * @return UzerJob[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getJobs(): array
    {
        $collection = $this->collectionFactory->create();
        return $collection->addStoreFilter($this->_storeManager->getStore()->getId())->addFieldToFilter('status', array('eq' => 1))->load()->getItems();
    }

    /**
     * @return Escaper
     */
    public function getEscaper(): Escaper
    {
        return $this->_escaper;
    }


}
