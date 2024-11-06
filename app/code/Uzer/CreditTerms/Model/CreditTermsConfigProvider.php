<?php

namespace Uzer\CreditTerms\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Uzer\CreditTerms\Model\ResourceModel\CustomerBalance\CollectionFactory;

class CreditTermsConfigProvider implements ConfigProviderInterface
{
    const CODE = 'terms';

    protected CollectionFactory $collectionFactory;
    protected Session $session;

    /**
     * @param CollectionFactory $collectionFactory
     * @param Session $session
     */
    public function __construct(CollectionFactory $collectionFactory, Session $session)
    {
        $this->collectionFactory = $collectionFactory;
        $this->session = $session;
    }


    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getConfig(): array
    {
        $quote = $this->session->getQuote();
        /** @var CustomerBalance $customerBalance */
        $customerBalance = $this->collectionFactory->create()->addFieldToFilter('customers_id', $quote->getCustomerId())->load()->getFirstItem();
        if (!$customerBalance->hasData()) {
            return [
                'payment' => array(
                    self::CODE => array()
                )
            ];
        }
        return array(
            'payment' => array(
                self::CODE => array(
                    'total' => $quote->getGrandTotal(),
                    'available' => $customerBalance->getValue()
                )
            )
        );
    }
}
