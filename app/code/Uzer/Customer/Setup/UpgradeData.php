<?php


namespace Uzer\Customer\Setup;


use Magento\Customer\Model\Customer;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Model\Config;

class UpgradeData implements UpgradeDataInterface
{

    private EavSetupFactory $eavSetupFactory;
    private Config $eavConfig;

    public function __construct(EavSetupFactory $eavSetupFactory, Config $eavConfig)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     * @throws \Exception
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.3', '<')) {
            $this->addField($setup, 'company_data', 'Company');
            $this->addField($setup, 'title_data', 'Title', true);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.5', '<')) {
            $this->addField($setup, 'phone', 'Phone Number');
        }
        $this->addField($setup, 'area_code', 'Código de area');
    }


    /**
     * @throws LocalizedException
     * @throws \Exception
     */
    public function addField(ModuleDataSetupInterface $setup, string $code, string $label, bool $required = false)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            Customer::ENTITY,
            $code,
            [
                'type' => 'varchar',
                'label' => $label,
                'input' => 'text',
                'required' => $required,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]
        );
        $titleAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, $code);
        $titleAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer', 'customer_account_edit', 'customer_account_create']
        );
        $titleAttribute->save();
    }
}
