<?php

namespace Uzer\Middleware\Console\Command;

use Magento\Customer\Model\ResourceModel\CustomerFactory as CustomerFactory;
use Magento\Customer\Model\CustomerFactory as CustomerModelFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\OrderFactory as ResourceModelFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Uzer\Middleware\Api\OrderIntegrationInterface;
use Uzer\Middleware\Logger\Logger;

class OrderMiddlewareIndex extends Command
{
    const NAME = 'order';
    protected ResourceModelFactory $resourceModelFactory;
    protected CollectionFactory $collectionFactory;
    protected OrderFactory $orderFactory;
    protected OrderIntegrationInterface $orderIntegration;
    protected Logger $logger;
    protected State $state;
    protected CustomerFactory $customerResourceFactory;
    protected CustomerModelFactory $customerFactory;


    /**
     * Initialization of the command.
     */
    protected function configure(): void
    {
        $this->setName('middleware:index:orders');
        $this->setDescription('Reindex order and send to middleware');
        $this->addOption(
            self::NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'Order id'
        );
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
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->init();
        $orderId = $input->getOption(self::NAME);
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
        if ($orderId) {
            /** @var Order $order */
            $order = $this->collectionFactory->create()->addAttributeToSelect('*')
                ->addFieldToFilter('entity_id', array('eq' => $orderId))
                ->load()->getFirstItem();
            $this->resourceModelFactory->create()->load($order, $order->getId());
            $customer = $this->customerFactory->create();
            $this->customerResourceFactory->create()->load($customer, $order->getCustomerId());
            $order->setCustomer($customer);
            $this->orderIntegration->save($order);

        } else {
            $collection = $this->collectionFactory->create();
            /** @var Order[] $orders */
            $orders = $collection->addAttributeToSelect('*')
                ->addFieldToFilter('state', array('eq' => Order::STATE_PROCESSING))
                ->getItems();
            foreach ($orders as $order) {
                try {
                    $this->resourceModelFactory->create()->load($order, $order->getId());
                    $customer = $this->customerFactory->create();
                    $this->customerResourceFactory->create()->load($customer, $order->getCustomerId());
                    $order->setCustomer($customer);
                    $this->orderIntegration->save($order);
                } catch (\Exception $ex) {
                    $this->logger->info('Error processing order: ' . $ex->getMessage());
                }
            }
        }
    }

    public function init()
    {
        $objectManager = ObjectManager::getInstance();
        $this->collectionFactory = $objectManager->create(CollectionFactory::class);
        $this->resourceModelFactory = $objectManager->create(ResourceModelFactory::class);
        $this->orderIntegration = $objectManager->create(OrderIntegrationInterface::class);
        $this->logger = $objectManager->create(Logger::class);
        $this->state = $objectManager->get(State::class);
        $this->customerResourceFactory = $objectManager->create(CustomerFactory::class);
        $this->customerFactory = $objectManager->create(CustomerModelFactory::class);
    }
}
