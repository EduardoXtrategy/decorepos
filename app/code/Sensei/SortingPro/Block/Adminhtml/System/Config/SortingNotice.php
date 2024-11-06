<?php

declare(strict_types=1);

namespace Sensei\SortingPro\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;

class SortingNotice extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * @var \Sensei\SortingPro\Model\Di\Wrapper
     */
    private $ruleCollection;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \Sensei\SortingPro\Model\Di\Wrapper $ruleCollection,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);
        $this->ruleCollection = $ruleCollection;
    }

    public function render(AbstractElement $element)
    {
        $this->setElement($element);
        $header = $this->_getHeaderHtml($element);

        $elements = $this->_getChildrenElementsHtml($element);

        $footer = $this->_getFooterHtml($element);

        $notice = $this->generateNoticeMessageHtml();

        return   $header . $notice . $elements . $footer;
    }

    /**
     * @return string
     */
    private function generateNoticeMessageHtml(): string
    {
        $content = '';
        return $content;
    }
}
