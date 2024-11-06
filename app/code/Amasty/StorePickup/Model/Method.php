<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Model;

use Magento\Framework\Model\AbstractModel;

class Method extends AbstractModel
{
    public const MEDIA_MODULE_DIRECTORY = 'amasty_storepick';

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploadFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    protected function _construct()
    {
        $this->_init(\Amasty\StorePickup\Model\ResourceModel\Method::class);
    }

    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Model\Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploadFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        parent::__construct($context, $coreRegistry);
        $this->uploadFactory = $uploadFactory;
        $this->filesystem = $filesystem;
    }

    public function massChangeStatus($ids, $status)
    {
        foreach ($ids as $id) {
            $model = $this->load($id);
            $model->setIsActive($status);
            $model->save();
        }

        return $this;
    }

    public function getFreeTypes(): array
    {
        $result = [];
        $freeTypesString = trim((string)$this->getData('free_types'), ',');
        if ($freeTypesString) {
            $result = explode(',', $freeTypesString);
        }

        return $result;
    }

    /**
     * @param $file
     * @return string
     */
    public function saveImage($file)
    {
        $uploader = $this->uploadFactory->create(['fileId' => $file]);
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'svg']);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $mediaDirectory = $this->filesystem->getDirectoryRead(
            \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
        );
        $result = $uploader->save(
            $mediaDirectory->getAbsolutePath(self::MEDIA_MODULE_DIRECTORY)
        );
        $img = self::MEDIA_MODULE_DIRECTORY . $result['file'];

        return $img;
    }
}
