<?php

namespace Uzer\Sales\Block\Order\Info;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;
use Magento\Sales\Model\ResourceModel\Order\Shipment\Collection;

class Shipments extends \Magento\Sales\Block\Order\Info
{

    protected ShipmentRepositoryInterface $shipmentRepository;
    protected $_template = 'Uzer_Sales::order/info/shipments.phtml';

    public function __construct(
        TemplateContext             $context,
        Registry                    $registry,
        PaymentHelper               $paymentHelper,
        AddressRenderer             $addressRenderer,
        ShipmentRepositoryInterface $shipmentRepository,
        array                       $data = [])
    {
        parent::__construct($context, $registry, $paymentHelper, $addressRenderer, $data);
        $this->shipmentRepository = $shipmentRepository;
    }

    /**
     *
     * @return false|Collection|\Magento\Sales\Model\Order\Shipment[]
     */
    public function getShipments()
    {
        return $this->getOrder()->getShipmentsCollection();
    }

}
