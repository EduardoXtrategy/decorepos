<?php

namespace Uzer\Search\Ui\Component\Form;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\System\Store;
use Magento\Ui\Component\Form\FieldFactory;
use Uzer\Search\Model\ProductBannerModel;
use Uzer\Search\Model\ProductBannerModelFactory;
use Uzer\Search\Model\ResourceModel\ProductBannerResourceFactory;

class Fieldset extends \Magento\Ui\Component\Form\Fieldset
{
    protected FieldFactory $fieldFactory;
    protected ProductBannerResourceFactory $productBannerResourceFactory;
    protected ProductBannerModelFactory $productBannerModelFactory;
    protected ?ProductBannerModel $productBannerModel = null;
    protected Store $store;
    private array $value = [];
    private ?string $contentValue = '';

    public function __construct(
        ContextInterface             $context,
        FieldFactory                 $fieldFactory,
        ProductBannerResourceFactory $productBannerResourceFactory,
        ProductBannerModelFactory    $productBannerModelFactory,
        Store                        $store,
        array                        $components = [],
        array                        $data = []
    )
    {
        parent::__construct($context, $components, $data);
        $this->fieldFactory = $fieldFactory;
        $this->productBannerResourceFactory = $productBannerResourceFactory;
        $this->productBannerModelFactory = $productBannerModelFactory;
        $this->store = $store;
    }


    /**
     * @return array|\Magento\Framework\View\Element\UiComponentInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getChildComponents(): array
    {
        $entityId = $this->context->getRequestParam('entity_id');
        if ($entityId && is_null($this->productBannerModel) && count($this->value) <= 0) {
            $this->productBannerModel = $this->productBannerModelFactory->create();
            $this->productBannerResourceFactory->create()->load($this->productBannerModel, $entityId);
            $this->value = $this->productBannerModel->getStoreId();
            $this->contentValue = $this->productBannerModel->getContent();
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
        $this->addComponent('store_id', $field);
        $components = parent::getChildComponents();
        $fieldContent = $components['content'];
        $data = $fieldContent->getData();
        $data['config']['value'] = $this->contentValue;
        $fieldContent->setData($data);
        return $components;
    }
}
