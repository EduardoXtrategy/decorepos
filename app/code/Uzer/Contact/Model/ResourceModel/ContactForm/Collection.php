<?php

namespace Uzer\Contact\Model\ResourceModel\ContactForm;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Contact\Model\ContactForm;
use Uzer\Contact\Model\ResourceModel\ContactForm as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'contact_form_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ContactForm::class, ResourceModel::class);
    }
}
