<?php

namespace Uzer\CustomProducts\Block\Customer\Widget;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;
use Uzer\CustomProducts\Model\ResourceModel\CategoryCustomer\CollectionFactory as CategoryCollectionFactory;
use Uzer\CustomProducts\Model\ResourceModel\CustomerProduct\CollectionFactory;

class MainNavigation extends Template implements BlockInterface
{

    protected $_template = 'Uzer_CustomProducts::widget/navigation.phtml';
    protected Session $session;
    protected CollectionFactory $collectionFactory;
    protected CategoryCollectionFactory $categoryCollectionFactory;

    /**
     * @param Context $context
     * @param Session $session
     * @param CollectionFactory $collectionFactory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context          $context,
        Session                   $session,
        CollectionFactory         $collectionFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        array                     $data = []
    )
    {
        parent::__construct($context, $data);
        $this->session = $session;
        $this->collectionFactory = $collectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    public function hasCustomProducts(): bool
    {
        try {
            if ($this->session->isLoggedIn()) {
                $total = $this->categoryCollectionFactory->create()
                    ->addFieldToFilter('customer_id', array('eq' => $this->session->getCustomerId()))
                    ->count();
                return $total > 0;
            }
        } catch (\Exception $ex) {

        }
        return false;
    }

}
