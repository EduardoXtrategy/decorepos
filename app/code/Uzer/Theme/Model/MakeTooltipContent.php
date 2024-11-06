<?php

namespace Uzer\Theme\Model;

use Magento\Cms\Model\Template\FilterProvider;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Theme\Helper\Data;

class MakeTooltipContent
{

    protected StoreManagerInterface $storeManager;
    protected FilterProvider $filterProvider;
    protected Data $data;

    /**
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     * @param Data $data
     */
    public function __construct(StoreManagerInterface $storeManager, FilterProvider $filterProvider, Data $data)
    {
        $this->storeManager = $storeManager;
        $this->filterProvider = $filterProvider;
        $this->data = $data;
    }


    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Exception
     */
    public function gateCompiledCode(): ?string
    {
        $content = $this->data->getTooltip($this->storeManager->getStore()->getId());
        if (!empty($content)) {
            return $this->filterProvider->getBlockFilter()->filter($content);
        }
        return null;
    }


}
