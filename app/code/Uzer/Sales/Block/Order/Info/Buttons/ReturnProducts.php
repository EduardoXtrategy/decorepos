<?php

namespace Uzer\Sales\Block\Order\Info\Buttons;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;
use Magento\Framework\Data\Form\FormKey;
use Uzer\Sales\Helper\Data;
use Magento\Sales\Model\Config;

class ReturnProducts extends \Magento\Sales\Block\Order\Info
{
    protected FormKey $formKey;
    protected Data $helperData;

    public function __construct(
        TemplateContext $context,
        Registry        $registry,
        PaymentHelper   $paymentHelper,
        AddressRenderer $addressRenderer,
        FormKey         $formKey,
        Data            $helperData,
        array           $data = []
    )
    {
        parent::__construct(
            $context,
            $registry,
            $paymentHelper,
            $addressRenderer,
            $data
        );
        $this->formKey = $formKey;
        $this->helperData = $helperData;
    }


    /**
     * @throws LocalizedException
     */
    public function getPostData(): array
    {
        return [
            'order' => $this->getOrder()->getId(),
            'uenc' => $this->formKey->getFormKey(),
        ];
    }

    /**
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isEnable(): bool
    {
        return $this->helperData->isEnable($this->_storeManager->getStore()->getId());
    }

    public function isDisplay(): bool
    {
        return $this->getOrder()->getStatus() == Order::STATE_COMPLETE;
    }
}
