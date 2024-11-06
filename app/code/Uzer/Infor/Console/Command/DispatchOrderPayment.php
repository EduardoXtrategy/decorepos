<?php

namespace Uzer\Infor\Console\Command;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\CustomerFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\OrderFactory as OrderResourceFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uzer\Infor\Model\Api\ApiDispatcher;

class DispatchOrderPayment extends Command
{
    protected OrderFactory $orderFactory;
    protected OrderResourceFactory $resourceOrderFactory;

    protected ApiDispatcher $apiDispatcher;
    protected State $state;


    /**
     * @param OrderFactory $orderFactory
     * @param OrderResourceFactory $resourceOrderFactory
     * @param ApiDispatcher $apiDispatcher
     * @param State $state
     * @param string|null $name
     */
    public function __construct(
        OrderFactory         $orderFactory,
        OrderResourceFactory $resourceOrderFactory,
        ApiDispatcher        $apiDispatcher,
        State                $state,
        string               $name = null
    ) {
        parent::__construct($name);
        $this->orderFactory = $orderFactory;
        $this->resourceOrderFactory = $resourceOrderFactory;
        $this->apiDispatcher = $apiDispatcher;
        $this->state = $state;
    }

    protected function configure()
    {
        $this->setName('infor:order:payment')
            ->setDescription('Dispatch order payment command');
        $this->addArgument('order_id', InputArgument::REQUIRED, 'Order ID');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
        $orderId = $input->getArgument('order_id');
        if (!$orderId) {
            $output->writeln('Order ID is required');
            return;
        }
        $output->writeln('Order ID: ' . $orderId);
        $order = $this->orderFactory->create();
        $this->resourceOrderFactory->create()->load($order, $orderId, 'increment_id');
        $customer = ObjectManager::getInstance()->create(Customer::class);
        ObjectManager::getInstance()->create(CustomerFactory::class)->create()->load($customer, $order->getCustomerId());
        $this->apiDispatcher->updateLineItems($order, 'F');
        return 0;
    }
}
