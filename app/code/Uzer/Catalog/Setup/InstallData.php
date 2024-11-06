<?php

namespace Uzer\Catalog\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Uzer\Catalog\Model\Common;

class InstallData implements InstallDataInterface
{


    protected EavSetupFactory $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->addBoxSize($setup);
    }

    /**
     * @throws \Zend_Validate_Exception
     * @throws LocalizedException
     */
    public function addBoxSize(ModuleDataSetupInterface $setup)
    {
        $code = Common::BOX_SIZE;
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $code);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $code,
            [
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Box size',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General',
                'visible' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'nullable' => true
            ]
        );
        $ATTRIBUTE_GROUP = 'General';
        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $allAttributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);
        foreach ($allAttributeSetIds as $attributeSetId) {
            $groupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $ATTRIBUTE_GROUP);
            $eavSetup->addAttributeToGroup(
                $entityTypeId,
                $attributeSetId,
                $groupId,
                $code,
                null
            );
        }
    }
}
