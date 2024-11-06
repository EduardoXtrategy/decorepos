<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter;

class AdditionalFieldMapper
{
    const ES_DATA_TYPE_STRING = 'string';
    const ES_DATA_TYPE_TEXT = 'text';
    const ES_DATA_TYPE_FLOAT = 'float';
    const ES_DATA_TYPE_INT = 'integer';
    const ES_DATA_TYPE_DATE = 'date';

    /** @deprecated */
    const ES_DATA_TYPE_ARRAY = 'array';

    private $fields = [];

    private $customerSession;

    private $storeManager;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $fields = []
    ) {
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->fields = $fields;
    }

    public function afterGetAllAttributesTypes($subject, array $result)
    {
        foreach ($this->fields as $fieldName => $fieldType) {
            if (is_object($fieldType) && ($fieldType instanceof AdditionalFieldMapperInterface)) {
                $attributeTypes = $fieldType->getAdditionalAttributeTypes();
                $result = array_merge($result, $attributeTypes);
                continue;
            }

            if (empty($fieldName)) {
                continue;
            }
            if ($this->isValidFieldType($fieldType)) {
                $result[$fieldName] = ['type' => $fieldType];
            }
        }

        return $result;
    }

    public function afterBuildEntityFields($subject, array $result)
    {
        return $this->afterGetAllAttributesTypes($subject, $result);
    }

    public function aroundGetFieldName($subject, callable $proceed, $attributeCode, $context = [])
    {
        if (isset($this->fields[$attributeCode]) && is_object($this->fields[$attributeCode])) {
            $filedMapper = $this->fields[$attributeCode];
            if ($filedMapper instanceof AdditionalFieldMapperInterface) {
                return $filedMapper->getFiledName($context);
            }
        }
        return $proceed($attributeCode, $context);
    }

    public function aroundMapFieldName($subject, callable $proceed, $fieldName)
    {
        if (isset($this->fields[$fieldName]) && is_object($this->fields[$fieldName])) {
            $filedMapper = $this->fields[$fieldName];
            if ($filedMapper instanceof AdditionalFieldMapperInterface) {
                $context = [
                    'customerGroupId' => $this->customerSession->getCustomerGroupId(),
                    'websiteId'       => $this->storeManager->getWebsite()->getId()
                ];
                return $filedMapper->getFiledName($context);
            }
        }
        return $proceed($fieldName);
    }

    private function isValidFieldType($fieldType)
    {
        switch ($fieldType) {
            case self::ES_DATA_TYPE_STRING:
            case self::ES_DATA_TYPE_DATE:
            case self::ES_DATA_TYPE_INT:
            case self::ES_DATA_TYPE_FLOAT:
                break;
            default:
                $fieldType = false;
                break;
        }
        return $fieldType;
    }
}
