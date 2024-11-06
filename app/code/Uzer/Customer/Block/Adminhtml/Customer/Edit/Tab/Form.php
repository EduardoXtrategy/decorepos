<?php

namespace Uzer\Customer\Block\Adminhtml\Customer\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Uzer\CreditTerms\Model\CustomerBalance;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory;
use Uzer\CreditTerms\Model\ResourceModel\CustomerBalance\CollectionFactory as BalanceCollectionFactory;

class Form extends Generic
{

    /**
     * @var string
     */
    protected string $targetForm = 'customer_creditterms_form';
    protected CollectionFactory $collectionFactory;
    protected BalanceCollectionFactory $balanceCollectionFactory;


    public function __construct(
        Context                  $context,
        Registry                 $registry,
        FormFactory              $formFactory,
        CollectionFactory        $collectionFactory,
        BalanceCollectionFactory $balanceCollectionFactory,
        array                    $data = []
    )
    {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->collectionFactory = $collectionFactory;
        $this->balanceCollectionFactory = $balanceCollectionFactory;
    }


    protected
    function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('creditterms_global_');
        $form->setFieldNameSuffix('creditterms_global_terms');

        $params = $this->getRequest()->getParams();
        $enable = false;
        $balanceValue = 0;
        if (!empty($params['id'])) {
            $customerId = $params['id'];
            /** @var InformationBusiness $informationBusiness */
            $informationBusiness = $this->collectionFactory->create()
                ->addFieldToFilter('customers_id', array('eq' => $customerId))
                ->load()
                ->getFirstItem();
            if ($informationBusiness->hasData()) {
                $enable = $informationBusiness->getPaymentTerms();
            }
            /**  @var CustomerBalance $balance */
            $balance = $this->balanceCollectionFactory->create()
                ->addFieldToFilter('customers_id', array('eq' => $customerId))
                ->load()
                ->getFirstItem();
            if ($balance->hasData()) {
                $balanceValue = $balance->getValue();
            }
        } else {
            $customerId = null;
        }


        $fieldset = $form->addFieldset(
            'creditterms_global_terms',
            ['legend' => __('Options:')]
        );
        $fieldset->addField(
            'credit_terms',
            'checkbox',
            [
                'name' => 'credit_terms',
                'title' => __('Enable credit terms'),
                'label' => __('Enable credit terms'),
                'note' => '',
                'value' => 1,
                'checked' => $enable ? 'checked' : '',
                'data-form-part' => 'customer_form'
            ]
        );

        $fieldset->addField(
            'global_credit_terms',
            'text',
            [
                'name' => 'global_credit_terms',
                'title' => __('Credit terms value'),
                'label' => __('Credit terms value'),
                'note' => '',
                'value' => $balanceValue,
                'data-form-part' => 'customer_form'
            ]
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
