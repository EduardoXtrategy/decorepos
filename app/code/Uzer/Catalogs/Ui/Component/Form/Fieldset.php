<?php

namespace Uzer\Catalogs\Ui\Component\Form;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Form\FieldFactory;
use Magento\Store\Model\System\Store;
use Uzer\Catalogs\Model\Catalog;
use Uzer\Catalogs\Model\CatalogFactory;
use Uzer\Catalogs\Model\ResourceModel\CatalogFactory as ResourceModelFactory;

class Fieldset extends \Magento\Ui\Component\Form\Fieldset
{
    protected FieldFactory $fieldFactory;
    protected Store $store;
    protected ResourceModelFactory $resourceModel;
    protected CatalogFactory $catalogFactory;
    protected ?Catalog $catalog = null;
    private array $value = [];

    public function __construct(
        ContextInterface     $context,
        FieldFactory         $fieldFactory,
        ResourceModelFactory $resourceModel,
        CatalogFactory       $catalogFactory,
        Store                $store,
        array                $components = [], array $data = []
    )
    {
        parent::__construct($context, $components, $data);
        $this->fieldFactory = $fieldFactory;
        $this->resourceModel = $resourceModel;
        $this->catalogFactory = $catalogFactory;
        $this->store = $store;
    }


    /**
     * @return array|\Magento\Framework\View\Element\UiComponentInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getChildComponents(): array
    {
        $entityId = $this->context->getRequestParam('id');
        if ($entityId && is_null($this->catalog) && count($this->value) <= 0) {
            $this->catalog = $this->catalogFactory->create();
            $this->resourceModel->create()->load($this->catalog, $entityId);
            $this->value = $this->catalog->getStoreId();
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
        ]);
        $field->prepare();
        $this->addComponent('store_id', $field);
        return parent::getChildComponents();
    }

}
