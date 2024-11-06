<?php

namespace Uzer\Customer\Block\Adminhtml\Customer\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Template;
use MageWorx\CustomerPrices\Model\CustomerPrices as CustomerPricesModel;
use MageWorx\CustomerPrices\Model\Encoder;
use MageWorx\CustomerPrices\Model\ResourceModel\CustomerPrices;

class CreditTerms extends Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'Uzer_Customer::customer/edit/customers.phtml';

    /**
     * Block Form
     */
    protected $blockForm;

    /**
     * CustomerPrice constructor.
     *
     * @param Context $context
     * @param Encoder $jsonEncoder
     * @param CustomerPrices $customerResourceModel
     * @param array $data
     */
    public function __construct(
        Context        $context,
        Encoder        $jsonEncoder,
        CustomerPrices $customerResourceModel,
        array          $data = []
    )
    {
        $this->jsonEncoder = $jsonEncoder;
        $this->customerResourceModel = $customerResourceModel;
        parent::__construct($context, $data);
    }

    /**
     * @return BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockForm(): BlockInterface
    {
        if (null === $this->blockForm) {
            $this->blockForm = $this->getLayout()->createBlock(
                \Uzer\Customer\Block\Adminhtml\Customer\Edit\Tab\Form::class,
                'customer.terms.form'
            );
        }

        return $this->blockForm;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Credit terms');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle(): Phrase
    {
        return $this->getTabLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab(): bool
    {
        return (bool)$this->getRequest()->getParam('id');
    }

    /**
     * {@inheritdoc}
     */
    public function isAjaxLoaded(): bool
    {
        return false;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFormHtml(): string
    {
        return $this->getBlockForm()->toHtml();
    }

    /**
     * @return string
     */
    public function getFieldId(): string
    {
        return 'credit_terms';
    }

    public function getNameInLayout(): string
    {
        return 'uzer_decowraps_credit_terms_global_fieldset';
    }
}
