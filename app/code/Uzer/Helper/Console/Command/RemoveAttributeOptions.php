<?php

namespace Uzer\Helper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;

class RemoveAttributeOptions extends Command
{

    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    protected EavSetupFactory $eavSetupFactory;

    /**
     * EAV product attribute factory
     *
     * @var AttributeFactory
     */
    protected AttributeFactory $attributeFactory;

    protected ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeFactory $attributeFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param string|null $name
     */
    public function __construct(EavSetupFactory $eavSetupFactory, AttributeFactory $attributeFactory, ModuleDataSetupInterface $moduleDataSetup, string $name = null)
    {
        parent::__construct($name);
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeFactory = $attributeFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }


    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('uzer:remove-attribute:options');
        $this->setDescription('Remove attributes options');
        parent::configure();
    }


    private array $attributes = [
        'application',
        'color',
        'display_material',
        'header',
        'holiday',
        'location',
        'generic_color',
        'material',
        'perforation',
        'product_type_decowraps',
        'season',
        'size',
        'sustainable',
        'terra_header',
        'product_line',
        'b_model'
    ];

    /**
     * CLI command description
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        foreach ($this->attributes as $attributeCode) {
            /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
            $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE);
            // IMPORTANT:
            // use $this->attributeFactory->create() before loading the attribute,
            // or else the options you want to delete will be cached and you cannot
            // delete other options from a second attribute in the same request
            $attribute = $this->attributeFactory->create()->loadByCode($entityTypeId, $attributeCode);
            $options = $attribute->getOptions();
            $optionsToRemove = [];
            foreach ($options as $option) {
                if ($option['value']) {
                    $optionsToRemove['delete'][$option['value']] = true;
                    $optionsToRemove['value'][$option['value']] = true;
                }
            }
            $eavSetup->addAttributeOption($optionsToRemove);
        }
        $output->writeln('<info>The product options has been deleted</info>');
    }
}
