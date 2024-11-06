<?php

namespace Uzer\Helper\Console\Command;

use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Symfony\Component\Console\Question\Question;

class RemoveOrders extends Command
{

    protected OrderRepositoryInterface $orderRepository;
    protected InvoiceRepositoryInterface $invoiceRepository;
    protected CreditmemoRepositoryInterface $creditmemoRepository;
    protected ShipmentRepositoryInterface $shipmentRepository;
    protected CartRepositoryInterface $cartRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected Registry $registry;
    protected State $state;

    public function __construct(
        OrderRepositoryInterface      $orderRepository,
        InvoiceRepositoryInterface    $invoiceRepository,
        CreditmemoRepositoryInterface $creditmemoRepository,
        ShipmentRepositoryInterface   $shipmentRepository,
        CartRepositoryInterface       $cartRepository,
        SearchCriteriaBuilder         $searchCriteriaBuilder,
        Registry                      $registry,
        State                         $state,
        string                        $name = null)
    {
        parent::__construct($name);
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->registry = $registry;
        $this->state = $state;
        $this->invoiceRepository = $invoiceRepository;
        $this->creditmemoRepository = $creditmemoRepository;
        $this->shipmentRepository = $shipmentRepository;
        $this->cartRepository = $cartRepository;
    }


    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('remove:orders');
        $this->setDescription('Remove all orders');
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
        $helper = $this->getHelper('question');
        $question = new Question('Enter password: ');
        $value = $helper->ask($input, $output, $question);
        if ($value == 'UzerDecowraps2023*') {
            $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
            $this->registry->register('isSecureArea', 'true');
            $output->writeln('<info>Removing orders</info>');
            $this->removeOrders();
            $output->writeln('<info>Removing Invoices</info>');
            $this->removeInvoices();
            $output->writeln('<info>Removing credit memos</info>');
            $this->removeCreditMemos();
            $output->writeln('<info>Removing carts</info>');
            $this->removeCarts();
            $this->registry->unregister('isSecureArea');
        } else {
            $output->writeln('<error>Incorrect password</error>');
        }
    }

    private function removeOrders()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->orderRepository->getList($searchCriteria);
        $orders = $searchResults->getItems();
        foreach ($orders as $order) {
            $this->orderRepository->delete($order);
        }
    }

    private function removeInvoices()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->invoiceRepository->getList($searchCriteria);
        $orders = $searchResults->getItems();
        foreach ($orders as $order) {
            $this->invoiceRepository->delete($order);
        }
    }

    private function removeCreditMemos()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->creditmemoRepository->getList($searchCriteria);
        $orders = $searchResults->getItems();
        foreach ($orders as $order) {
            $this->creditmemoRepository->delete($order);
        }
    }

    private function removeCarts()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->cartRepository->getList($searchCriteria);
        $orders = $searchResults->getItems();
        foreach ($orders as $order) {
            $this->cartRepository->delete($order);
        }
    }
}
