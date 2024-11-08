<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Helper;

/**
 * @deprecared
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;
    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->objectManager = $objectManager;
        $this->eavConfig = $eavConfig;
        parent::__construct($context);
        $this->escaper = $escaper;
    }

    public function getStatuses()
    {
        return [
            '0' => __('Inactive'),
            '1' => __('Active'),
        ];
    }

    public function getCountries($needHash = false)
    {
        /**
         * @var \Magento\Directory\Model\Country $countriesModel
         */
        $countriesModel = $this->objectManager->get(\Magento\Directory\Model\Country::class);
        $countries = $countriesModel->getCollection()->toOptionArray();
        unset($countries[0]);

        if ($needHash) {
            $countries = $this->_toHash($countries);
        }

        return $countries;
    }

    public function getStates($needHash = false)
    {
        /**
         * @var \Magento\Directory\Model\Region $stateModel
         */
        $regionModel = $this->objectManager->get(\Magento\Directory\Model\Region::class);
        $regions = $regionModel->getCollection()->toOptionArray();
        $regions = $this->_addCountriesToStates($regions);

        if ($needHash) {
            $regions = $this->_toHash($regions);
        }

        return $regions;
    }

    public function getTypes($needHash = false)
    {
        $options = [];

        $attribute = $this->eavConfig->getAttribute('catalog_product', 'am_shipping_type');
        if ($attribute->usesSource()) {
            $options = $attribute->getSource()->getAllOptions(false);
        }

        if ($needHash) {
            $options = $this->_toHash($options, false);
        }

        return $options;
    }

    /**
     * @param $zip
     * @return array('area', 'district')
     */
    public function getDataFromZip($zip)
    {
        $dataZip = ['area' => '', 'district' => ''];

        if (!empty($zip)) {
            $zipSpell = str_split($zip);
            foreach ($zipSpell as $element) {
                if ($element === ' ') {
                    break;
                }
                if (is_numeric($element)) {
                    $dataZip['district'] = $dataZip['district'] . $element;
                } elseif (empty($dataZip['district'])) {
                    $dataZip['area'] = $dataZip['area'] . $element;
                }
            }
        }

        return $dataZip;
    }

    protected function _addCountriesToStates($regions)
    {
        $hashCountry = $this->getCountries(true);
        foreach ($regions as $key => $region) {
            if (isset($region['country_id'])) {
                $regions[$key]['label'] = $hashCountry[$region['country_id']] . "/" . $region['label'];
            }
        }

        return $regions;
    }

    protected function _toHash($options, $needSort = true)
    {
        $hash = [];
        foreach ($options as $option) {
            $hash[$option['value']] = $option['label'];
        }
        if ($needSort) {
            asort($hash);
        }
        $hashAll['0'] = 'All';
        $hash = $hashAll + $hash;
        $options = $hash;

        return $options;
    }

    public function escapeHtml($string)
    {
        return $this->escaper->escapeHtml($string, ['b', 'i', 'u', 's']);
    }
}
