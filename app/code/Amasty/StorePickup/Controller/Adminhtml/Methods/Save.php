<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Controller\Adminhtml\Methods;

use Amasty\Base\Model\Serializer;
use Amasty\StorePickup\Model\LabelFactory;
use Amasty\StorePickup\Model\ResourceModel\Label;
use Amasty\StorePickup\Model\ResourceModel\Label\CollectionFactory;
use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = "Amasty_StorePickup::amstorepick";

    /**
     * @var LabelFactory
     */
    private $labelFactory;

    /**
     * @var Serializer
     */
    private $serializerBase;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Label
     */
    private $resourceLabel;

    public function __construct(
        Action\Context $context,
        LabelFactory $labelFactory,
        CollectionFactory $collectionFactory,
        Label $resourceLabel,
        Serializer $serializerBase
    ) {
        parent::__construct($context);
        $this->labelFactory = $labelFactory;
        $this->serializerBase = $serializerBase;
        $this->collectionFactory = $collectionFactory;
        $this->resourceLabel = $resourceLabel;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /**
         * @var \Amasty\StorePickup\Model\Method $modelMethod
         */
        $modelMethod = $this->_objectManager->get(\Amasty\StorePickup\Model\Method::class);
        /**
         * @var \Amasty\StorePickup\Model\Rate $modelRate
         */
        $modelRate = $this->_objectManager->create(\Amasty\StorePickup\Model\Rate::class);
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $modelMethod->setData($data);
            $modelMethod->setId($id);

            try {
                $this->prepareCommentImgForSave($modelMethod);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $this->resultRedirectFactory->create()->setPath('*/*/edit', ['id' => $id]);
            }

            try {
                $noFile = false;
                $this->prepareForSave($modelMethod);
                $modelMethod->save();
                $modelMethodId = $modelMethod->getId();
                $this->prepareForSaveLabels($data, $modelMethodId);
                if ($modelMethod->getData('import_clear')) {
                    $modelRate->deleteBy($modelMethodId);
                }

                try {
                    /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
                    $uploader = $this->_objectManager->create(
                        \Magento\MediaStorage\Model\File\Uploader::class,
                        ['fileId' => 'import_file']
                    );
                } catch (\Exception $e) {
                    $noFile = true;
                }

                // import files
                if (!$noFile) {
                    $uploader->setAllowedExtensions('csv');
                    $fileData = $uploader->validateFile();

                    $fileName = $fileData['tmp_name'];
                    // @TODO: may be problems with detect line endings in csv files. Just uncomment this
                    // ini_set('auto_detect_line_endings', 1);

                    $errors = $modelRate->import($modelMethodId, $fileName);
                    foreach ($errors as $err) {
                        $this->messageManager->addErrorMessage($err);
                    }
                }

                $msg = __('Shipping rates have been successfully saved');
                $this->messageManager->addSuccessMessage($msg);
                if ($this->getRequest()->getParam('back')) {
                    return $this->resultRedirectFactory->create()->setPath('*/*/edit', ['id' => $modelMethodId]);
                } else {
                    return $this->resultRedirectFactory->create()->setPath('*/*');
                }
            } catch (\Exception $e) {
                $errMessage = $e->getMessage();
                if ($errMessage == 'Disallowed file type.') {
                    $errMessage = $errMessage . ' Please use CSV format of file for import';
                }
                $this->messageManager->addErrorMessage($errMessage);
                return $this->resultRedirectFactory->create()->setPath('*/*/edit', ['id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('Unable to find a record to save'));

        return $this->resultRedirectFactory->create()->setPath('*/*');
    }

    public function prepareForSave($model)
    {
        $fields = ['stores', 'cust_groups', 'free_types'];
        foreach ($fields as $f) {
            // convert data from array to string
            $val = $model->getData($f);
            $model->setData($f, '');
            if (is_array($val)) {
                // need commas to simplify sql query
                $model->setData($f, ',' . implode(',', $val) . ',');
            }
        }

        return true;
    }

    /**
     * @param $data
     * @param $methodId
     */
    private function prepareForSaveLabels($data, $methodId)
    {
        $storesData = [];
        if (isset($data['label_']) && is_array($data['label_'])) {
            foreach ($data['label_'] as $store => $storeLabel) {
                $storesData[$store]['label'] = $storeLabel;
            }
        }

        if (isset($data['comment_']) && is_array($data['comment_'])) {
            foreach ($data['comment_'] as $store => $storeComment) {
                $storesData[$store]['comment'] = $storeComment;
            }
        }
        foreach ($storesData as $storeId => $storeData) {
            /** @var \Amasty\StorePickup\Model\Label $modelLabel */
            $modelLabel = $this->labelFactory->create();
            if (isset($data['id'])) {
                $modelLabel = $this->collectionFactory->create()
                    ->addFieldToFilter('method_id', $data['id'])
                    ->addFieldToFilter('store_id', $storeId)
                    ->getFirstItem();
            }
            $modelLabel->setStoreId($storeId);
            $modelLabel->setLabel($storeData['label']);
            $modelLabel->setComment($storeData['comment']);
            $modelLabel->setMethodId($methodId);
            $this->resourceLabel->save($modelLabel);
        }
    }

    /**
     * @param $modelMethod
     *
     * @return $this
     */
    private function prepareCommentImgForSave($modelMethod)
    {
        $files = $this->getRequest()->getFiles();
        $image = $modelMethod->getCommentImg();

        if ($files && isset($files['comment_img']) && strlen($files['comment_img']['name'])) {
            $img = $modelMethod->saveImage($files['comment_img']);
            $modelMethod->setCommentImg($img);
        } elseif ($image && isset($image['delete']) && $image['delete']) {
            $modelMethod->setCommentImg('');
        } elseif ($image && isset($image['value']) && $image['value']) {
            $modelMethod->setCommentImg($image['value']);
        }

        return $this;
    }
}
