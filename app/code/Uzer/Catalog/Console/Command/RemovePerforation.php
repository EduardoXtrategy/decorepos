<?php

namespace Uzer\Catalog\Console\Command;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\State;
use Magento\ConfigurableProduct\Model\Type;

class RemovePerforation extends Command
{

    protected ProductRepositoryInterface $productRepository;
    protected State $state;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder      $searchCriteriaBuilder,
        State                      $state,
        string                     $name = null
    )
    {
        parent::__construct($name);
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->state = $state;
    }

    protected function configure()
    {
        $this->setName('uzer:catalog:remove-perforation')->setDescription('Removes perforation from catalog products');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('type_id', 'configurable')->create();
        $products = $this->productRepository->getList($searchCriteria);
        $counter = 0;
        foreach ($products->getItems() as $product) {
            $product->setOptions([]);
            try {
                $this->productRepository->save($product);
            } catch (\Exception $ex) {
                $output->writeln(sprintf('Error saving: %s; %s', $product->getSku(), $ex->getMessage()));
            }
            $output->writeln(sprintf('Run %s of %s; %s', $counter, $products->getTotalCount(), $product->getSku()));
            $counter++;
        }
        $output->writeln('Perforation removed successfully!');
    }
}
