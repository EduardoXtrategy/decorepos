<?php

namespace Uzer\Blog\Block\Widget;

use Magento\Framework\Data\Collection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Rokanthemes\Blog\Model\Category;
use Rokanthemes\Blog\Model\Post;
use Rokanthemes\Blog\Model\ResourceModel\Category\CollectionFactory;

class Widget extends Template implements BlockInterface
{
    protected $_template = "widget.phtml";
    /**
     * @var CollectionFactory
     */
    private $categoryCollectionFactory;
    /**
     * @var \Rokanthemes\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    private $postCollectionFactory;
    /**
     * @var Category[]
     */
    private $categories = array();

    public function __construct(
        Template\Context                                             $context,
        CollectionFactory                                            $categoryCollectionFactory,
        \Rokanthemes\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        array                                                        $data = []
    )
    {
        parent::__construct($context, $data);
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->postCollectionFactory = $postCollectionFactory;
    }

    /**
     * @return \Rokanthemes\Blog\Model\Category[]|array
     */
    public function getCategories()
    {
        if (count($this->categories) <= 0) {
            $categories = explode(',', $this->getData('categories'));
            $categoryCollection = $this->categoryCollectionFactory->create();
            $this->categories = $categoryCollection->addFieldToFilter('category_id', array('in' => $categories))->load()->getItems();
        }
        return $this->categories;
    }

    /**
     * @return  Post[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPosts()
    {
        $posts = array();
        foreach ($this->categories as $category) {
            $categoryPosts = $this->postCollectionFactory->create()
                ->addCategoryFilter($category)
                ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->addOrder('publish_time', Collection::SORT_ORDER_DESC)
                ->setPageSize(3)->load()->getItems();
            $posts[$category->getId()] = $categoryPosts;
        }
        return $posts;
    }

    /**
     * @return
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaFolder()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAllPost()
    {
        return $this->_storeManager->getStore()->getUrl('blog');
    }

}
