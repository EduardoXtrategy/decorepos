<?php

namespace Uzer\Samples\Ui\DataProvider;

use Amasty\Rolepermissions\Helper\Data;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Uzer\Samples\Model\ResourceModel\SampleOrder\CollectionFactory;
use Uzer\Samples\Model\SampleOrder;

/**
 * DataProvider component.
 */
class SampleOrderDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magento\Ui\DataProvider\AddFieldToCollectionInterface[]
     */
    protected $addFieldStrategies;

    /**
     * @var \Magento\Ui\DataProvider\AddFilterToCollectionInterface[]
     */
    protected $addFilterStrategies;
    /**
     * @var PoolInterface
     */
    private PoolInterface $modifiersPool;
    private Data $ruleHelper;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        Data $ruleHelper,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = [],
        PoolInterface $modifiersPool = null
    )
    {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
        $this->collection = $collectionFactory->create();
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
        $this->modifiersPool = $modifiersPool ?: ObjectManager::getInstance()->get(PoolInterface::class);
        $this->ruleHelper = $ruleHelper;
    }

    public function getData(): array
    {
        if (!$this->getCollection()->isLoaded()) {
            $storeViews = $this->ruleHelper->currentRule()->getData('scope_storeviews');
            if ($storeViews) {
                $this->collection->addStoreFilter($storeViews);
            }
            $this->getCollection()->load();
        }
        $data = $this->getCollection()->toArray();
        /** @var ModifierInterface $modifier */
        foreach ($this->modifiersPool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }
        $customerRepository = ObjectManager::getInstance()->get(CustomerRepositoryInterface::class);
        $resourceModel = \Magento\Framework\App\ObjectManager::getInstance()->create(\Magento\Customer\Model\ResourceModel\Customer::class);
        $resourceConnection = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Framework\App\ResourceConnection::class);
        $storeRepository = ObjectManager::getInstance()->create(\Magento\Store\Api\StoreRepositoryInterface::class);
        foreach ($data['items'] as $key => $item) {
            if (isset($item['email']) && !empty($item['email'])) {
                try {
                    $customer = $customerRepository->get($item['email']);
                    $table = $resourceConnection->getTableName('customer_entity_varchar');
                    $connection = $resourceConnection->getConnection();
                    $attributeId = $resourceModel->getAttribute('company_data')->getId();
                    $result = $connection->fetchAll('SELECT * FROM ' . $table . ' WHERE attribute_id = ' . $attributeId . ' AND entity_id = ' . $customer->getId(), ['value']);
                    if (count($result) > 0) {
                        $item['company'] = $result[0]['value'];
                    } else {
                        $item['company'] = '';
                    }
                } catch (\Exception $ex) {
                    $item['company'] = '';
                }
            } else {
                $item['company'] = '';
            }
            if (isset($item['store_id']) && !empty($item['store_id'])) {
                try {
                    $store = $storeRepository->getById($item['store_id']);
                    $item['store'] = $store->getName();
                } catch (\Exception $ex) {
                    $item['store'] = '';
                }
            } else {
                $item['store'] = '';
            }
            $data['items'][$key] = $item;
        }
        return $data;
    }

    /**
     * Add field to select
     *
     * @param string|array $field
     * @param string|null $alias
     * @return void
     */
    public function addField($field, $alias = null)
    {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]->addField($this->getCollection(), $field, $alias);
        } else {
            parent::addField($field, $alias);
        }
    }

    /**
     * @inheritdoc
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
        } else {
            parent::addFilter($filter);
        }
    }

    /**
     * @inheritdoc
     * @since 103.0.0
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        /** @var ModifierInterface $modifier */
        foreach ($this->modifiersPool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}
