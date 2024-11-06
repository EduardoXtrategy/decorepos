<?php

namespace Uzer\Helper\Console\Command;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\StoreRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uzer\Helper\Model\SetDimensions;

class SetDimensionAttributes extends Command
{

    protected SetDimensions $setDimensions;

    public function __construct(SetDimensions $setDimensions, string $name = null)
    {
        parent::__construct($name);
        $this->setDimensions = $setDimensions;
    }


    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('products:dimensions:set');
        $this->setDescription('Set products dimension');
        parent::configure();
    }

    /**
     * CLI command description.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws NoSuchEntityException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->setDimensions->execute($input, $output);
    }
}
