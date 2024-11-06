<?php


namespace Uzer\Location\Config\Source;


use Magento\Eav\Model\Config;

class AttributeValue implements \Magento\Framework\Data\OptionSourceInterface
{
    private Config $eavConfig;

    /**
     * AttributeValue constructor.
     * @param Config $eavConfig
     */
    public function __construct(Config $eavConfig)
    {
        $this->eavConfig = $eavConfig;
    }


    /**
     * @return array|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        $attribute = $this->eavConfig->getAttribute('catalog_product', 'location');
        $options = $attribute->getSource()->getAllOptions();
        $item = array();
        foreach ($options as $option) {
            $item[] = array(
                'label' => $option['label'],
                'value' => $option['label']
            );
        }
        return $item;
    }
}
