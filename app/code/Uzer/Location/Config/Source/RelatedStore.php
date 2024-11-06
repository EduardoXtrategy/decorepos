<?php


namespace Uzer\Location\Config\Source;


use Magento\Store\Api\StoreRepositoryInterface;

class RelatedStore implements \Magento\Framework\Data\OptionSourceInterface
{

    private StoreRepositoryInterface $storeRepository;

    /**
     * RelatedStore constructor.
     * @param StoreRepositoryInterface $storeRepository
     */
    public function __construct(StoreRepositoryInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }


    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $stores = $this->storeRepository->getList();
        $items[] = array(
            'label' => __('Please select'),
            'value' => 0
        );
        foreach ($stores as $store) {
            $items[] = array(
                'label' => $store->getName(),
                'value' => $store->getCode()
            );
        }
        return $items;
    }
}
