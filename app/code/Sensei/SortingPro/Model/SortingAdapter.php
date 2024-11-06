<?php

namespace Sensei\SortingPro\Model;

/**
 * Class SortingAdapter
 * adapter of @see \Magento\Eav\Model\Entity\Attribute
 * used for add sorting method
 */
class SortingAdapter extends \Magento\Framework\DataObject
{
    const CACHE_TAG = 'SORTING_METHOD';

    private $helper;

    private $methodModel;

    public function __construct(
        \Sensei\SortingPro\Helper\Data $helper,
        \Sensei\SortingPro\Api\MethodInterface $methodModel = null,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->methodModel = $methodModel;
        parent::__construct($data);
        $this->prepareData();
    }

    private function prepareData()
    {
        if (!$this->hasData('attribute_code')) {
            $this->setData('attribute_code', $this->methodModel->getMethodCode());
        }
        if (!$this->hasData('frontend_label')) {
            $this->setData('frontend_label', $this->methodModel->getMethodName());
        }
    }

    public function getAttributeCode()
    {
        if ($this->hasData('attribute_code')) {
            return $this->_getData('attribute_code');
        }
        return $this->methodModel->getMethodCode();
    }

    public function getFrontendLabel()
    {
        if ($this->hasData('frontend_label')) {
            return $this->getData('frontend_label');
        }

        return $this->methodModel->getMethodName();
    }

    public function getDefaultFrontendLabel()
    {
        return $this->getFrontendLabel();
    }

    public function getStoreLabel($storeId = null)
    {
        if ($this->hasData('store_label')) {
            return $this->getData('store_label');
        }

        return $this->methodModel->getMethodLabel($storeId);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getAttributeCode()];
    }

    public function setMethodModel($methodModel)
    {
        $this->methodModel = $methodModel;
        return $this;
    }

    public function getMethodModel()
    {
        return $this->methodModel;
    }

    public function getFrontendInput()
    {
        return 'hidden';
    }

    public function getName()
    {
        return $this->getAttributeCode();
    }

    public function usesSource()
    {
        return false;
    }
}
