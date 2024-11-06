<?php

namespace Uzer\Samples\Model;

use Magento\Framework\App\Response\Http;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManager;
use Uzer\Samples\Api\Data\SamplesCartInterfaceFactory;
use Uzer\Samples\Command\SamplesCart\SaveCommand;
use Uzer\Samples\Model\ResourceModel\SamplesCart as ResourceModel;
use Uzer\Samples\Model\ResourceModel\SamplesCart\CollectionFactory;

class Session extends \Magento\Framework\Session\SessionManager
{
    const SESSION_KEY = 'samples_cart_item';
    protected \Magento\Framework\Session\Generic $_session;
    protected ManagerInterface $_eventManager;
    protected Http $response;
    protected SamplesCartFactory $samplesCartFactory;
    protected SamplesCartInterfaceFactory $samplesCartInterfaceFactory;
    protected SaveCommand $saveCommand;
    protected \Magento\Customer\Model\Session $customerSession;
    private CollectionFactory $collectionFactory;
    private StoreManager $storeManager;

    public function __construct(
        \Magento\Framework\App\Request\Http                    $request,
        \Magento\Framework\Session\SidResolverInterface        $sidResolver,
        \Magento\Framework\Session\Config\ConfigInterface      $sessionConfig,
        \Magento\Framework\Session\SaveHandlerInterface        $saveHandler,
        \Magento\Framework\Session\ValidatorInterface          $validator,
        \Magento\Framework\Session\StorageInterface            $storage,
        \Magento\Framework\Stdlib\CookieManagerInterface       $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\App\Http\Context                    $httpContext,
        \Magento\Framework\App\State                           $appState,
        \Magento\Framework\Session\Generic                     $session,
        ManagerInterface                                       $eventManager,
        Http                                                   $response,
        SamplesCartFactory                                     $samplesCartFactory,
        SaveCommand                                            $saveCommand,
        SamplesCartInterfaceFactory                            $samplesCartInterfaceFactory,
        \Magento\Customer\Model\Session                        $customerSession,
        CollectionFactory                                      $collectionFactory,
        StoreManager                                           $storeManager
    )
    {
        parent::__construct(
            $request,
            $sidResolver,
            $sessionConfig,
            $saveHandler,
            $validator,
            $storage,
            $cookieManager,
            $cookieMetadataFactory,
            $appState
        );
        $this->_session = $session;
        $this->_eventManager = $eventManager;
        $this->response = $response;
        $this->_eventManager->dispatch('samples_cart_session_init', ['samples_cart_session' => $this]);
        $this->samplesCartInterfaceFactory = $samplesCartInterfaceFactory;
        $this->customerSession = $customerSession;
        $this->samplesCartFactory = $samplesCartFactory;
        $this->saveCommand = $saveCommand;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function hasSamplesCart(): bool
    {
        if (!$this->customerSession->isLoggedIn()) {
            return false;
        }
        $collection = $this->collectionFactory->create();
        $count = $collection
            ->addFieldToFilter('customer_id', array('eq' => $this->customerSession->getCustomerId()))
            ->addFieldToFilter('active', array('eq' => 1))
            ->addOrder('entity_id')
            ->count();
        return $count > 0;
    }


    /**
     * @return SamplesCart
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\SessionException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSamplesCart(): SamplesCart
    {
        if ($this->hasSamplesCart()) {
            $collection = $this->collectionFactory->create();
            /** @var SamplesCart $samplesCart */
            $samplesCart = $collection
                ->addFieldToFilter('customer_id', array('eq' => $this->customerSession->getCustomerId()))
                ->addFieldToFilter('active', array('eq' => 1))
                ->addOrder('entity_id')
                ->load()->getFirstItem();
            if ($samplesCart->getActive()) {
                return $samplesCart;
            }
        }
        $samplesCart = $this->samplesCartInterfaceFactory->create();
        $samplesCart->setActive(true);
        $samplesCart->setCustomerId($this->customerSession->getCustomerId());
        $samplesCart->setStoreId($this->storeManager->getStore()->getId());
        $samplesCart->setWebsiteId($this->storeManager->getStore()->getWebsiteId());
        return $this->saveCommand->execute($samplesCart);
    }
}
