<?php
namespace Uzer\PaymentMethods\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Uzer\PaymentMethods\Helper\Data as ShippingHelper;

class Index extends Action
{
    protected $shippingHelper;

    public function __construct(
        Context $context,
        ShippingHelper $shippingHelper
    ) {
        $this->shippingHelper = $shippingHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        // Obtener los métodos de envío
        $methods = $this->shippingHelper->getAllShippingMethods();
        
        // Mostrar los métodos de envío en formato JSON
        echo json_encode($methods, JSON_PRETTY_PRINT);
        exit;
    }
}
