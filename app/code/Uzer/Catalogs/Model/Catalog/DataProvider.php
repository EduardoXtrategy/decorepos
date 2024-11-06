<?php


namespace Uzer\Catalogs\Model\Catalog;


use Uzer\Catalogs\Model\Catalog;
use Uzer\Catalogs\Model\ResourceModel\Catalog\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    private $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $contactCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $contactCollectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $contactCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

    }


    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = [];
        /** @var Catalog $catalog */
        foreach ($items as $catalog) {
            $data = $catalog->getData();
            unset($data['image']);
            $data['icon'][0] = [
                'name' => $catalog->getImageName(),
                'url' => $catalog->getImage()
            ];
            $this->loadedData[$catalog->getId()] = $data;
        }

        return $this->loadedData;

    }

}
