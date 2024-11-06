<?php
namespace Uzer\PaymentMethods\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Shipping\Model\Config as ShippingConfig;

class Data extends AbstractHelper
{
    protected $shippingConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ShippingConfig $shippingConfig
    ) {
        $this->shippingConfig = $shippingConfig;
        parent::__construct($context);
    }

    /**
     * Obtener todos los métodos de envío habilitados
     *
     * @return array
     */
    public function getAllShippingMethods()
    {
        $methods = $this->shippingConfig->getActiveCarriers();
        $shippingMethodsArray = [];

        foreach ($methods as $carrierCode => $carrierModel) {
            if ($carrierMethods = $carrierModel->getAllowedMethods()) {
                $carrierTitle = $carrierModel->getConfigData('title');
                foreach ($carrierMethods as $methodCode => $methodTitle) {
                    $shippingMethodsArray[] = [
                        'code' => $carrierCode . '_' . $methodCode,
                        'title' => $carrierTitle . ' - ' . $methodTitle,
                    ];
                }
            }
        }

        return $shippingMethodsArray;
    }
}
