<?php

namespace Uzer\Customer\Block\Account;

use Uzer\Customer\Model\ResourceModel\InformationBusinessFactory as ResourceModelFactory;
use Uzer\Customer\Model\InformationBusinessFactory as ModelFactory;

class Info extends \Magento\Customer\Block\Account\Dashboard\Info
{

    protected ResourceModelFactory $resourceModelFactory;
    protected ModelFactory $modelFactory;
    protected ?string $payableEmail = null;

    /**
     *
     * {@inheritdoc}
     * @see \Magento\Customer\Block\Account\Dashboard\Info::__construct()
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Helper\View $helperView,
        ResourceModelFactory $resourceModelFactory,
        ModelFactory $modelFactory,
        array $data = array()
    ) {
        parent::__construct($context, $currentCustomer, $subscriberFactory, $helperView, $data);
        $this->resourceModelFactory = $resourceModelFactory;
        $this->modelFactory = $modelFactory;
    }

    public function getTaxvat()
    {
        return $this->getCustomer()->getTaxvat();
    }

    public function getPayableEmail()
    {
        if (!$this->payableEmail) {
            /** @var InformationBusiness $informationBusiness */
            $informationBusiness = $this->modelFactory->create();
            $this->resourceModelFactory->create()->load($informationBusiness, $this->getCustomer()
                ->getId(), 'customers_id');
            if ($informationBusiness->hasData()) {
                $this->payableEmail = $informationBusiness->getAccountEmail();
            }
        }
        return $this->payableEmail;
    }
}
