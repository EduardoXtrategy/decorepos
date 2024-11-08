<?php

namespace Amasty\Xsearch\Block\Search;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Blog extends AbstractSearch
{
    const BLOG_BLOCK_PAGE = 'blog';

    /**
     * @var AbstractCollection
     */
    private $postsSearchCollection;

    /**
     * @var AbstractCollection
     */
    private $categoriesSearchCollection;

    /**
     * @var AbstractCollection
     */
    private $tagsSearchCollection;

    /**
     * @return string
     */
    public function getBlockType()
    {
        return self::BLOG_BLOCK_PAGE;
    }

    /**
     * @inheritdoc
     */
    protected function generateCollection()
    {
        $collection = parent::generateCollection();

        foreach ($this->getPostsCollection() as $item) {
            $item->setUrl($item->getUrl());
            $this->addToBlogCollection($item, $collection);

            if (count($collection) == $this->getLimit()) {
                break;
            }
        }

        foreach ($this->getCategoriesCollection() as $item) {
            $item->setUrl($item->getUrl());
            $this->addToBlogCollection($item, $collection);

            if (count($collection) == $this->getLimit()) {
                break;
            }
        }

        foreach ($this->getTagsCollection() as $item) {
            $item->setUrl($item->getUrl());
            $this->addToBlogCollection($item, $collection);

            if (count($collection) == $this->getLimit()) {
                break;
            }
        }

        return $collection;
    }

    /**
     * @param $item
     * @param $collection
     */
    private function addToBlogCollection($item, &$collection)
    {
        $dataObject = $this->getData('dataObjectFactory')->create();
        $dataObject->setData($item->getData());
        $collection->addItem($dataObject);
    }

    /**
     * @return AbstractCollection
     */
    private function getPostsCollection()
    {
        if ($this->postsSearchCollection === null) {
            $this->postsSearchCollection = $this->getData('postsCollectionFactory')->create()
                ->addSearchFilter($this->getQuery()->getQueryText())
                ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->addFieldToFilter('status', 2);
        }

        return $this->postsSearchCollection;
    }

    /**
     * @return AbstractCollection
     */
    private function getCategoriesCollection()
    {
        if ($this->categoriesSearchCollection === null) {
            $this->categoriesSearchCollection = $this->getData('categoriesCollectionFactory')->create()
                ->addSearchFilter($this->getQuery()->getQueryText())
                ->addStoreWithDefault($this->_storeManager->getStore()->getId())
                ->addStatusFilter(1);
        }

        return $this->categoriesSearchCollection;
    }

    /**
     * @return AbstractCollection
     */
    private function getTagsCollection()
    {
        if ($this->tagsSearchCollection === null) {
            $this->tagsSearchCollection = $this->getData('tagsCollectionFactory')->create()
                ->addSearchFilter($this->getQuery()->getQueryText())
                ->addStoreWithDefault($this->_storeManager->getStore()->getId());
        }

        return $this->tagsSearchCollection;
    }

    /**
     * @param \Magento\Framework\DataObject $item
     * @return string
     */
    public function getSearchUrl(\Magento\Framework\DataObject $item)
    {
        return $item->getUrl();
    }

    /**
     * @param \Magento\Framework\DataObject $item
     * @return string
     */
    public function getName(\Magento\Framework\DataObject $item)
    {
        if (isset($item['post_id'])) {
            $name = $item->getTitle();
        } else {
            $name = $item->getName();
        }

        return $this->generateName($name);
    }

    /**
     * @inheritdoc
     */
    public function getDescription(\Magento\Framework\DataObject $item)
    {
        $descStripped = $this->stripTags($item->getShortContent(), null, true);
        $this->replaceVariables($descStripped);

        return $this->getHighlightText($descStripped);
    }

    /**
     * @return array[]
     */
    public function getIndexFulltextValues()
    {
        $postValues = $this->postsSearchCollection->getIndexFulltextValues();
        $categoryValues = $this->categoriesSearchCollection->getIndexFulltextValues();
        $tagValues = $this->tagsSearchCollection->getIndexFulltextValues();

        return array_merge($postValues, $categoryValues, $tagValues);
    }
}
