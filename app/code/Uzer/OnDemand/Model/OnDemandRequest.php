<?php

namespace Uzer\OnDemand\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\OnDemand\Model\ResourceModel\OnDemandRequest as ResourceModel;

class OnDemandRequest extends AbstractModel
{


    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_ondemand_requests_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->getData('description');
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->setData('description', $description);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->setData('name', $name);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->getData('email');
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->setData('email', $email);
    }

    /**
     * @return mixed
     */
    public function getCustomersId()
    {
        return $this->getData('customers_id');
    }

    /**
     * @param mixed $customers_id
     */
    public function setCustomersId($customers_id): void
    {
        $this->setData('customers_id', $customers_id);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->setData('created_at', $created_at);
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->getData('updated_at');
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->setData('updated_at', $updated_at);
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->getData('store_id');
    }

    /**
     * @param mixed $store_id
     */
    public function setStoreId($store_id): void
    {
        $this->setData('store_id', $store_id);
    }

    /**
     * @return mixed
     */
    public function getProductSku()
    {
        return $this->getData('product_sku');
    }

    /**
     * @param mixed $store_id
     */
    public function setProductSku($productSku): void
    {
        $this->setData('product_sku', $productSku);
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->getData('product_id');
    }

    /**
     * @param mixed $store_id
     */
    public function setProductId($productId): void
    {
        $this->setData('product_id', $productId);
    }

    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->getData('product_name');
    }

    /**
     * @param mixed $store_id
     */
    public function setProductName($productId): void
    {
        $this->setData('product_name', $productId);
    }

    /**
     * @return array|mixed|null
     */
    public function getBModelId()
    {

        return $this->getData('b_model_id');
    }

    /**
     * @param mixed $bModelId
     */
    public function setBModelId($bModelId): void
    {
        $this->setData('b_model_id', $bModelId);
    }
}
