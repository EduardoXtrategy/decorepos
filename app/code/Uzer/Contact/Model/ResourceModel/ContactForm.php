<?php

namespace Uzer\Contact\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ContactForm extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'contact_form_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('contact_form', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
