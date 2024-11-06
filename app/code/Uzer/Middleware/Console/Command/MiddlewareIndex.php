<?php

namespace Uzer\Middleware\Console\Command;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModelCustomer;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory;
use Uzer\Middleware\Model\CustomerIntegration;

class MiddlewareIndex extends Command
{

    protected CollectionFactory $collectionFactory;
    protected ResourceModelCustomer $resourceModelCustomer;
    protected CustomerFactory $customerFactory;
    protected AddressRepositoryInterface $addressRepository;
    protected CustomerIntegration $customerIntegration;

    public function __construct(
        CollectionFactory          $collectionFactory,
        ResourceModelCustomer      $resourceModelCustomer,
        CustomerFactory            $customerFactory,
        AddressRepositoryInterface $addressRepository,
        CustomerIntegration        $customerIntegration,
        string                     $name = null)
    {
        $this->collectionFactory = $collectionFactory;
        $this->customerFactory = $customerFactory;
        $this->addressRepository = $addressRepository;
        $this->customerIntegration = $customerIntegration;
        $this->resourceModelCustomer = $resourceModelCustomer;
        parent::__construct($name);
    }


    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('middleware:index');
        $this->setDescription('New attempt manual for send customers');
        parent::configure();
    }

    /**
     * CLI command description.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws AlreadyExistsException
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var InformationBusiness[] $items */
        $items = $this->collectionFactory->create()->addFieldToFilter('saved_middleware', array('eq' => false))->load()->getItems();
        $total = count($items);
        $counter = 1;
        foreach ($items as $item) {
            $output->writeln(sprintf('Attempt customer: %s; %s of %s', $item->getCustomersId(), $counter, $total));
            try{
                $customer = $this->customerFactory->create();
                $this->resourceModelCustomer->create()->load($customer, $item->getCustomersId());
                $address = $this->addressRepository->getById($item->getAddressesId());
                $this->customerIntegration->save($customer, $address, $item);
            }catch(\Exception $ex){

            }
            $counter++;
        }
    }
}
