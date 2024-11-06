<?php

namespace Uzer\Checkoutstep\Plugin\Block;


use Magento\Sales\Model\Order\Item as OrderItem;
use Magento\Tax\Block\Item\Price\Renderer;
use Uzer\Checkoutstep\Model\CustomFormat;

class ItemPriceRenderWrapper
{

    protected CustomFormat $customFormat;

    /**
     * @param CustomFormat $customFormat
     */
    public function __construct(CustomFormat $customFormat)
    {
        $this->customFormat = $customFormat;
    }


    public function afterFormatPrice(Renderer $subject, $result, $price)
    {
//        $item = $subject->getItem();
//        if ($item instanceof OrderItem) {
//            return $this->customFormat->formatLong($price);
//        }
        return $result;
    }

}
