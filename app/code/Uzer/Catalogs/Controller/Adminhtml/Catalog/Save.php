<?php


namespace Uzer\Catalogs\Controller\Adminhtml\Catalog;


use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Catalogs\Model\ImageUploader;
use Uzer\Catalogs\Model\ResourceModel\CatalogFactory;
use Uzer\Catalogs\Model\CatalogFactory as ModelFactory;

class Save extends Action implements HttpPostActionInterface
{

    private ImageUploader $imageUploader;
    private StoreManagerInterface $storeManager;
    private CatalogFactory $resourceModelFactory;
    private ModelFactory $catalogFactory;


    public function __construct(
        Context               $context,
        ImageUploader         $imageUploader,
        StoreManagerInterface $storeManager,
        CatalogFactory        $resourceModelFactory,
        ModelFactory          $catalogFactory
    )
    {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
        $this->storeManager = $storeManager;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->catalogFactory = $catalogFactory;
    }


    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $back = isset($data['back']);
        $image = null;
        $imageName = null;
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if (isset($data['icon'][0]['type'])) {
            if (isset($data['icon'][0]['name']) && isset($data['icon'][0]['tmp_name'])) {
                try {
                    $image = sprintf('%suzer/feature/%s', $mediaUrl, $data['icon'][0]['name']);
                    $imageName = $data['icon'][0]['name'];
                    $this->imageUploader->moveFileFromTmp($data['icon'][0]['name']);
                } catch (\Exception $ex) {

                }
            } else if (isset($data['icon'][0]['name']) && !isset($data['icon'][0]['tmp_name'])) {
                $image = sprintf('%s%s', $mediaUrl, str_replace('/media/', '', $data['icon'][0]['url']));
                $imageName = $data['icon'][0]['name'];
            }
            if (!is_null($image)) {
                $data['image'] = $image;
                $data['image_name'] = $imageName;
            }
        }

        $catalog = $this->catalogFactory->create();
        $resourceModel = $this->resourceModelFactory->create();
        if (isset($data['entity_id']) && !is_null($data['entity_id']) && !empty($data['entity_id']) && $data['entity_id'] > 0)
            $resourceModel->load($catalog, $data['entity_id']);
        unset($data['icon']);
        $catalog->setData($data);
        $resourceModel->save($catalog);
        $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        if ($back) {
            $redirect->setPath('*/*/edit/id/' . $catalog->getEntityId());
        } else {
            $redirect->setPath('*/*/index');
        }
        return $redirect;
    }
}
