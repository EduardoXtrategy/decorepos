<?php

namespace Uzer\Customer\Observer\Adminhtml;

use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModelFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory;
use Uzer\Customer\Model\ResourceModel\InformationBusinessFactory;

class AfterSaveCustomer implements ObserverInterface
{

    protected RequestInterface $request;
    protected CollectionFactory $collectionFactory;
    protected InformationBusinessFactory $resourceModelFactory;

    /**
     * @param RequestInterface $request
     * @param CollectionFactory $collectionFactory
     * @param InformationBusinessFactory $resourceModelFactory
     */
    public function __construct(
        RequestInterface           $request,
        CollectionFactory          $collectionFactory,
        InformationBusinessFactory $resourceModelFactory
    )
    {
        $this->request = $request;
        $this->collectionFactory = $collectionFactory;
        $this->resourceModelFactory = $resourceModelFactory;
    }


    /**
     * @throws AlreadyExistsException
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $observer->getCustomer();
        $creditTermsRequest = $this->request->getParam('creditterms_global_terms');
        /** @var InformationBusiness $informationBusiness */
        $informationBusiness = $this->collectionFactory->create()
            ->addFieldToFilter('customers_id', array('eq' => $customer->getId()))
            ->load()
            ->getFirstItem();
        $creditTerms = isset($creditTermsRequest['credit_terms']) && $creditTermsRequest['credit_terms'] == 1;
        if ($informationBusiness->hasData()) {
            $informationBusiness->setPaymentTerms($creditTerms);
            $this->resourceModelFactory->create()->save($informationBusiness);
        }
    }
}
