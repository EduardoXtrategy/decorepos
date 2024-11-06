<?php

namespace Uzer\Checkoutstep\Plugin\Model;

use Psr\Log\LoggerInterface;

class ShippingMethodManagement
{

    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function afterEstimateByAddress(\Magento\Quote\Model\ShippingMethodManagement $shippingMethodManagement, $output)
    {
        $this->logger->info('Enter here: ' . __METHOD__);
        return $this->filterOutput($output);
    }

    public function afterEstimateByExtendedAddress(\Magento\Quote\Model\ShippingMethodManagement $shippingMethodManagement, $output)
    {
        $this->logger->info('Enter here: ' . __METHOD__);
        return $this->filterOutput($output);
    }

    public function afterEstimateByAddressId(\Magento\Quote\Model\ShippingMethodManagement $shippingMethodManagement, $output)
    {
        $this->logger->info('Enter here: ' . __METHOD__);
        return $this->filterOutput($output);
    }

    public function afterGetList(\Magento\Quote\Model\ShippingMethodManagement $shippingMethodManagement, $output)
    {
        $this->logger->info('Enter here: ' . __METHOD__);
        return $this->filterOutput($output);
    }


    /**
     * @param \Magento\Quote\Api\Data\ShippingMethodInterface[] $output array of shipping methods.
     * @return array|mixed
     */
    private function filterOutput(array $output)
    {
        $carriers = [];
        foreach ($output as $key => $shippingMethod) {
            if ($shippingMethod->getAvailable()) {
                $carriers[$shippingMethod->getCarrierCode()] = $shippingMethod;
            } else {
                unset($output[$key]);
            }
        }
        if (isset($carriers['freeshipping'])) {
            unset($carriers['flatrate']);
            return $carriers;
        }
        return $output;
    }
}
