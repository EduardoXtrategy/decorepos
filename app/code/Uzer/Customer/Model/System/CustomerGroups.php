<?php

namespace Uzer\Customer\Model\System;

use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Data\OptionSourceInterface;

class CustomerGroups implements OptionSourceInterface
{

    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $_groupRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Convert\DataObject
     */
    protected $_objectConverter;

    /**
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder   $searchCriteriaBuilder
     * @param \Magento\Framework\Convert\DataObject          $objectConverter
     */
    public function __construct(
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Convert\DataObject $objectConverter
    ) {
        $this->_groupRepository       = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_objectConverter       = $objectConverter;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $groups = $this->_groupRepository->getList($this->_searchCriteriaBuilder->create())->getItems();
        $this->options = $this->_objectConverter->toOptionArray($groups, 'id', 'code');

        return $this->options;
    }
}
