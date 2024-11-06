<?php

namespace Uzer\Jobs\Ui\Component\Form;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\System\Store;
use Magento\Ui\Component\Form\FieldFactory;
use Uzer\Jobs\Model\ResourceModel\UzerJobFactory as ResourceModelFactory;
use Uzer\Jobs\Model\UzerJob;
use Uzer\Jobs\Model\UzerJobFactory;

class Fieldset extends \Magento\Ui\Component\Form\Fieldset
{

    protected FieldFactory $fieldFactory;
    protected ResourceModelFactory $resourceModelFactory;
    protected UzerJobFactory $uzerJobFactory;
    protected ?UzerJob $uzerJob = null;
    protected Store $store;
    private array $value = [];


    public function __construct(
        ContextInterface     $context,
        FieldFactory         $fieldFactory,
        ResourceModelFactory $resourceModelFactory,
        UzerJobFactory       $uzerJobFactory,
        Store                $store,
        array                $components = [],
        array                $data = []

    )
    {
        parent::__construct($context, $components, $data);
        $this->fieldFactory = $fieldFactory;
        $this->store = $store;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->uzerJobFactory = $uzerJobFactory;
    }


    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getChildComponents(): array
    {
        $entityId = $this->context->getRequestParam('entity_id');
        if ($entityId && is_null($this->uzerJob) && count($this->value) <= 0) {
            $this->uzerJob = $this->uzerJobFactory->create();
            $this->resourceModelFactory->create()->load($this->uzerJob, $entityId);
            $this->value = $this->uzerJob->getStoreId();
        }
        $field = $this->fieldFactory->create();
        $field->setData([
            'config' => [
                'label' => __('Store'),
                'value' => $this->value,
                'formElement' => 'multiselect',
            ],
            'options' => $this->store,
            'name' => 'store_id',
            'values' => array(1, 9)
        ]);
        $field->prepare();
        $this->addComponent('test', $field);
        return parent::getChildComponents();
    }

}
