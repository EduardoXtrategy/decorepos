<?php

namespace Uzer\DeliveryLatam\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Uzer\Core\Logger\Logger;
use Uzer\Core\Model\StringHelpers;
use Magento\Checkout\Model\Session;

class Carrier extends AbstractCarrier implements CarrierInterface
{

    protected $_code = 'deliverylatam';

    protected $_isFixed = true;

    protected $_rateResultFactory;

    protected MethodFactory $_rateMethodFactory;
    protected Session $_session;

    public function __construct(
        ScopeConfigInterface                                        $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory  $rateErrorFactory,
        \Psr\Log\LoggerInterface                                    $logger,
        \Magento\Shipping\Model\Rate\ResultFactory                  $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        Session                                                     $session,
        array                                                       $data = []
    )
    {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_session = $session;
    }

    /**
     *
     * @return float
     */
    private function getShippingPrice()
    {
        $configPrice = $this->getConfigData('price');

        $shippingPrice = $this->getFinalPriceWithHandlingFee($configPrice);

        return $shippingPrice;
    }

    /**
     * Collect and get rates
     *
     * @param RateRequest $request
     * @return \Magento\Framework\DataObject|bool|null
     * @api
     */
    public function collectRates(RateRequest $request)
    {
        $result = $this->_rateResultFactory->create();
        if (!$this->getConfigFlag('active')) {
            return false;
        }


        $region = $request->getDestRegionCode();
        $city = $request->getDestCity();
        $weight = $request->getPackageWeight();
        $pricePerKg = $this->getShippingPrice();
        $cityFormatted = $this->parseCity($city);
        $this->_logger->info('City formatted: ' . $cityFormatted . '; Price: ' . $pricePerKg);
        if ($region == 'CO-CUN' && (StringHelpers::startWith($cityFormatted, 'bogota') || StringHelpers::startWith($cityFormatted, 'bogotá'))) {
            $method = $this->_rateMethodFactory->create();
            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod($this->_code);
            $method->setMethodTitle($this->getConfigData('name'));
            $method->setPrice(0);
            $method->setCost(0);
            $result->append($method);
        } else if ($region == 'CO-ANT' && (StringHelpers::startWith($cityFormatted, 'rionegro') || StringHelpers::startWith($cityFormatted, 'rio negro'))) {
            $price = $pricePerKg * $weight;
            $this->_logger->info('Peso: ' . $weight . '; Precio: ' . $pricePerKg);
            if ($this->isFreeShipping()) {
                $price = 0;
            }
            $method = $this->_rateMethodFactory->create();
            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title_price'));
            $method->setMethod($this->_code);
            $method->setMethodTitle($this->getConfigData('name_price'));
            $method->setPrice($price);
            $method->setCost($price);
            $result->append($method);
        }
        return $result;
    }

    public function getAllowedMethods()
    {
        return [
            $this->_code => $this->getConfigData('name')
        ];
    }

    private function parseCity(string $city)
    {
        $city = trim($city);
        $city = strtolower($city);
        return StringHelpers::replaceAccentMark($city);
    }

    private function isFreeShipping()
    {
        $nits = explode(',', $this->getConfigData('customers_nit_free'));
        $customerVat = $this->_session->getQuote()->getCustomer()->getTaxvat();
        return !empty($nits) && in_array($customerVat, $nits);
    }
}
