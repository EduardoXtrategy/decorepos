<?php


namespace Uzer\Blog\Model\Config\Source;


use Rokanthemes\Blog\Model\Category;

class Select implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @var \Rokanthemes\Blog\Model\ResourceModel\Category\CollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * Select constructor.
     * @param \Rokanthemes\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     */
    public function __construct(\Rokanthemes\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory)
    {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }


    public function toOptionArray()
    {
        /** @var Category[] $categories */
        $categories = $this->categoryCollectionFactory->create()->load()->getItems();
        $items = array();
        foreach ($categories as $category) {
            $items[] = array(
                'value' => $category->getId(),
                'label' => $category->getTitle()
            );
        }
        return $items;
    }
}
