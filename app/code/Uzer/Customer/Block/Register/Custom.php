<?php

namespace Uzer\Customer\Block\Register;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class Custom extends Template
{

    protected array $_templates = [
        'us_en' => 'Uzer_Customer::custom/us_template.phtml',
        'us_es' => 'Uzer_Customer::custom/us_template.phtml',
        'eu_fr' => 'Uzer_Customer::custom/eu_template.phtml',
        'eu_en' => 'Uzer_Customer::custom/eu_template.phtml',
        'eu_es' => 'Uzer_Customer::custom/eu_template.phtml',
        'eu_ger' => 'Uzer_Customer::custom/eu_template.phtml',
        'eu_ned' => 'Uzer_Customer::custom/eu_template.phtml',
        'lat_es' => 'Uzer_Customer::custom/lat_template.phtml',
        'lat_en' => 'Uzer_Customer::custom/lat_template.phtml',
        'ec_en' => 'Uzer_Customer::custom/ec_template.phtml'
    ];


    protected function _beforeToHtml()
    {
        return parent::_beforeToHtml();
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getTemplate(): string
    {
        $code = $this->_storeManager->getStore()->getCode();
        $template = 'Uzer_Customer::register/empty';
        if (isset($this->_templates[$code])) {
            $template = $this->_templates[$code];
        }
        $this->setTemplate($template);
        return $this->_template;
    }


    public function getDownloadLink(): string
    {
        return '';
    }

    /**
     * @param string $path
     * @return string
     * @throws NoSuchEntityException
     */
    public function getStoreUrl(string $path = ''): string
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK) . $path;
    }
}
