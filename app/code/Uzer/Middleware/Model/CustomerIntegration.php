<?php

namespace Uzer\Middleware\Model;

use Magento\Framework\Exception\AlreadyExistsException;
use Uzer\Core\Api\CustomerIntegrationInterface;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Customer\Model\ResourceModel\InformationBusinessFactory as ResourceModelFactory;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Model\Customer;
use Uzer\Middleware\Logger\Logger;

class CustomerIntegration implements CustomerIntegrationInterface
{

    protected ResourceModelFactory $resourceModel;
    protected MiddlewareAuth $middlewareAuth;
    protected MiddlewareApi $middlewareApi;
    protected Logger $logger;

    /**
     * @param ResourceModelFactory $resourceModel
     * @param MiddlewareAuth $middlewareAuth
     * @param MiddlewareApi $middlewareApi
     * @param Logger $logger
     */
    public function __construct(ResourceModelFactory $resourceModel, MiddlewareAuth $middlewareAuth, MiddlewareApi $middlewareApi, Logger $logger)
    {
        $this->middlewareAuth = $middlewareAuth;
        $this->middlewareApi = $middlewareApi;
        $this->logger = $logger;
        $this->resourceModel = $resourceModel;
    }


    /**
     * @throws AlreadyExistsException
     */
    public function save(Customer $customer, AddressInterface $address, InformationBusiness $informationBusiness): void
    {
        $attempts = ($informationBusiness->getAttempts() ?? 0) + 1;
        $informationBusiness->setAttempts($attempts);
        if ($attempts > 4) {
            return;
        }
        try {
            $token = $this->middlewareAuth->auth();
            $this->middlewareApi->send($token, $customer, $address, $informationBusiness);
            $informationBusiness->setSavedMiddleware(true);
        } catch (\Exception $ex) {
            $informationBusiness->setSavedMiddleware(false);
            $this->logger->info('Error saving customer in middleware: ' . $ex->getMessage());
        }
        $this->resourceModel->create()->save($informationBusiness);
    }

}
