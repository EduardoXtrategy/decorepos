<?php

namespace Uzer\Infor\Console\Command;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceCustomerFactory;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uzer\Infor\Model\Api\ApiDispatcher;

class DispatchCustomerInfor extends Command
{

    protected CustomerFactory $customerFactory;
    protected ResourceCustomerFactory $resourceCustomerFactory;
    protected ApiDispatcher $apiDispatcher;

    /**
     * @param CustomerFactory $customerFactory
     * @param ResourceCustomerFactory $resourceCustomerFactory
     * @param ApiDispatcher $apiDispatcher
     * @param string|null $name
     */
    public function __construct(
        CustomerFactory         $customerFactory,
        ResourceCustomerFactory $resourceCustomerFactory,
        ApiDispatcher           $apiDispatcher,
        string                  $name = null
    )
    {
        parent::__construct($name);
        $this->customerFactory = $customerFactory;
        $this->resourceCustomerFactory = $resourceCustomerFactory;
        $this->apiDispatcher = $apiDispatcher;
    }


    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('infor:customer');
        $this->setDescription('Send customer information to inforerp');
        $this->addArgument('customer_id', InputArgument::REQUIRED, 'Customer ID');
        parent::configure();
    }

    /**
     * CLI command description.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws GuzzleException
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $customerId = $input->getArgument('customer_id');
        $customer = $this->customerFactory->create();
        $this->resourceCustomerFactory->create()->load($customer, $customerId);
        if (!$customer->getId()) {
            throw new LocalizedException(__('Customer with id %1 not found', $customerId));
        }
        $output->writeln('Customer ID: ' . $customer->getId());
        $this->apiDispatcher->customer($customer);
        $output->writeln('Customer information sent to InforERP');
    }
}
