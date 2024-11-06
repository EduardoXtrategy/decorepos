<?php

namespace Uzer\Samples\Block\Checkout;

use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template;
use Uzer\Samples\Block\Cart\BaseBlock;
use Uzer\Samples\Model\ResourceModel\SampleOrderFactory;

class Success extends BaseBlock
{

    protected SampleOrderFactory $sampleOrderFactory;
    protected RequestInterface $request;

    public function __construct(Template\Context $context, CurrencyFactory $currencyFactory, SampleOrderFactory $sampleOrderFactory, RequestInterface $request, array $data = [])
    {
        parent::__construct($context, $currencyFactory, $data);
        $this->sampleOrderFactory = $sampleOrderFactory;
        $this->request = $request;
    }


    public function getSampleOrder()
    {
        return $this->request->getParam('order');
    }

}
