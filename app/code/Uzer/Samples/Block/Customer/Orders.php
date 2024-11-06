<?php

namespace Uzer\Samples\Block\Customer;

use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template;
use Uzer\Samples\Block\Cart\BaseBlock;
use \Magento\Customer\Model\Session as CustomerSession;
use Uzer\Samples\Model\ResourceModel\SampleOrder\Collection;
use Magento\Framework\Data\Collection as DataCollection;

class Orders extends BaseBlock
{

    protected RequestInterface $request;
    private CustomerSession $customerSession;
    private Collection $collection;

    public function __construct(
        Template\Context $context,
        CurrencyFactory $currencyFactory,
        CustomerSession    $customerSession,
        Collection $collection,
        RequestInterface $request,
        array $data = []
    ) {
        parent::__construct($context, $currencyFactory, $data);
        $this->request = $request;
        $this->customerSession = $customerSession;
        $this->collection = $collection;
    }

    public function getSamplesOrders(): array
    {
        return $this->collection
            ->addFieldToFilter('customers_id', ['eq' => $this->customerSession->getCustomerId()])
            ->addOrder('entity_id',DataCollection::SORT_ORDER_DESC)
            ->load()
            ->getItems();

    }

}


