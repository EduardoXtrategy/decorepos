<?php

namespace Uzer\Samples\Model;

use Magento\Framework\App\ObjectManager;
use Uzer\Samples\Api\Data\SamplesCartInterface;
use Uzer\Samples\Model\ResourceModel\SampleCartItem\Collection;
use Uzer\Samples\Model\ResourceModel\SampleCartItem\CollectionFactory;
use Magento\Framework\Model\AbstractModel;
use Uzer\Samples\Model\ResourceModel\SamplesCart as ResourceModel;

class SamplesCart extends AbstractModel implements SamplesCartInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'samples_cart_model';
    protected CollectionFactory $collectionFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context                        $context,
        \Magento\Framework\Registry                             $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb           $resourceCollection = null,
        CollectionFactory                                       $collectionFactory,
        array                                                   $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->collectionFactory = $collectionFactory;
    }


    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }


    /**
     * @return SampleCartItem[]
     */
    public function getItems(): array
    {
        $items = array(0,NULL);
        $collection = ObjectManager::getInstance()->create(Collection::class);
        return $collection
            ->addFieldToFilter('samples_cart_id', array('eq' => $this->getEntityId()))
            ->load()        
            ->getItems();
    }

    public function getQtyItems(): int
    {
        $items = array(0,NULL);
        $collection = ObjectManager::getInstance()->create(Collection::class);
        return $collection
            ->addFieldToFilter('samples_cart_id', array('eq' => $this->getEntityId()))
            ->addFieldToFilter('is_parent', array('in' => $items))
            ->load()
            ->count();
    }

    public function getChildItems(): array
    {
        $items = array(0,Null);
        $collection = ObjectManager::getInstance()->create(Collection::class);
        return $collection
            ->addFieldToFilter('samples_cart_id', array('eq' => $this->getEntityId()))
            ->addFieldToFilter('is_parent', array('in' => $items))
            ->load()
            ->getItems();
    }


    /**
     * @inheritDoc
     */
    public function getCustomerId(): ?int
    {
        return $this->getData(self::CUSTOMER_ID) === null ? null
            : (int)$this->getData(self::CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId(?int $customerId): void
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritDoc
     */
    public function getActive(): ?bool
    {
        return $this->getData(self::ACTIVE) === null ? null
            : (bool)$this->getData(self::ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setActive(?bool $active): void
    {
        $this->setData(self::ACTIVE, $active);
    }

    /**
     * @inheritDoc
     */
    public function getStoreId(): ?int
    {
        return $this->getData(self::STORE_ID) === null ? null
            : (int)$this->getData(self::STORE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStoreId(?int $storeId): void
    {
        $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @inheritDoc
     */
    public function getWebsiteId(): ?int
    {
        return $this->getData(self::WEBSITE_ID) === null ? null
            : (int)$this->getData(self::WEBSITE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setWebsiteId(?int $websiteId): void
    {
        $this->setData(self::WEBSITE_ID, $websiteId);
    }

    /**
     * @inheritDoc
     */
    public function getCompleteAt(): ?string
    {
        return $this->getData(self::COMPLETE_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCompleteAt(?string $completeAt): void
    {
        $this->setData(self::COMPLETE_AT, $completeAt);
    }

    public function getCustomerAddressId(): ?int
    {
        return $this->getData(self::CUSTOMER_ADDRESS_ID);
    }

    public function setCustomerAddressId(int $customerAddressId): void
    {
        $this->setData(self::CUSTOMER_ADDRESS_ID, $customerAddressId);
    }
}
