<?php

namespace Uzer\CustomProducts\Console\Command;

use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uzer\CustomProducts\Command\CategoryCustomer\SaveCategoryByCustomer;
use Uzer\CustomProducts\Command\CategoryCustomer\SaveProductCategories;
use Uzer\CustomProducts\Command\CustomerProduct\CustomProductByCustomer;
use Uzer\CustomProducts\Model\ProductCategoriesMap;

class FactoryCategories extends Command
{

    protected CustomProductByCustomer $customProductByCustomer;
    protected SaveCategoryByCustomer $saveCategoryByCustomer;
    protected SaveProductCategories $saveProductCategories;
    protected State $state;

    /**
     * @param CustomProductByCustomer $customProductByCustomer
     * @param SaveCategoryByCustomer $saveCategoryByCustomer
     * @param SaveProductCategories $saveProductCategories
     * @param State $state
     * @param string|null $name
     */
    public function __construct(
        CustomProductByCustomer $customProductByCustomer,
        SaveCategoryByCustomer  $saveCategoryByCustomer,
        SaveProductCategories   $saveProductCategories,
        State                   $state,
        string                  $name = null)
    {
        parent::__construct($name);
        $this->customProductByCustomer = $customProductByCustomer;
        $this->saveCategoryByCustomer = $saveCategoryByCustomer;
        $this->saveProductCategories = $saveProductCategories;
        $this->state = $state;
    }


    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('factory:categories');
        $this->setDescription('Generate or update product categories for specific customer');
        parent::configure();
    }

    /**
     * CLI command description.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $products = [];
        $categoryProductMap = $this->customProductByCustomer->getProducts();
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $output->writeln('<info>' . count($categoryProductMap) . '</info>');
        $counterCustomers = 1;
        foreach ($categoryProductMap as $categoryProduct) {
            $output->writeln('<info>Saving customer: ' . $categoryProduct->getCustomerId() . '; ' . $counterCustomers . ' of ' . count($categoryProductMap) . '</info>');
            try {
                $this->saveCategoryByCustomer->execute($categoryProduct);
                if (!$categoryProduct->getCategoryId()) {
                    $counterCustomers++;
                    continue;
                }
                foreach ($categoryProduct->getProducts() as $sku) {
                    $product = $products[$sku] ?? new ProductCategoriesMap();
                    $product->setSku($sku);
                    $product->addCategory($categoryProduct->getCategoryId());
                    $product->setPosition($categoryProduct->getPosition());
                    $products[$sku] = $product;
                }
            } catch (\Exception $ex) {
                $output->writeln('<error>Error saving customer: ' . $categoryProduct->getCustomerId() . '; Exception: ' . $ex->getMessage() . '</error>');
            }
            $counterCustomers++;
        }
        $counter = 1;
        foreach ($products as $product) {
            try {
                $output->writeln('<info>Saving product: ' . $product->getSku() . '; ' . $counter . ' of ' . count($products) . '</info>');
                $this->saveProductCategories->execute($product);
            } catch (\Exception $ex) {
                $output->writeln('<error>Error saving product: ' . $ex->getMessage() . '</error>');
            }
            $counter++;
        }
        $output->writeln('<info>the category customers were generated</info>');
    }
}
