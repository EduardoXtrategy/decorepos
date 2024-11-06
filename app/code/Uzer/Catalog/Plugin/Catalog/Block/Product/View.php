<?php

namespace Uzer\Catalog\Plugin\Catalog\Block\Product;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Catalog\Helper\Data;

class View
{

    protected StoreManagerInterface $storeManager;
    protected Data $data;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Data $data
     */
    public function __construct(StoreManagerInterface $storeManager, Data $data)
    {
        $this->storeManager = $storeManager;
        $this->data = $data;
    }


    /**
     * @throws NoSuchEntityException
     */
    public function afterGetTemplate(\Magento\Catalog\Block\Product\View $view, $result)
    {
        if ($this->data->isEnable($this->storeManager->getStore()->getId())) {
            if ($this->endsWith($result, 'addtocart.phtml')) {
                return 'Uzer_Catalog::product/view/addtocart.phtml';
            }
            if ($this->endsWith($result, 'updatecart')) {
                return 'Uzer_Catalog::product/view/addtocart.phtml';
            }
        }
        return $result;
    }

    public function endsWith($haystack, $needle): bool
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }

}
