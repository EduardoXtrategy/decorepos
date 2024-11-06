<?php
/**
 * Copyright © 2015 RokanThemes.com. All rights reserved.
 * @author RokanThemes Team <contact@rokanthemes.com>
 */

namespace Rokanthemes\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Rokanthemes\Blog\Model\Post;
use Rokanthemes\Blog\Model\ResourceModel\Post\Collection;

/**
 * Blog post save controller
 */
class Save extends \Rokanthemes\Blog\Controller\Adminhtml\Post
{

    private Collection $previousPosts;

    public function __construct(Context $context)
    {
        parent::__construct($context);
    }


    /**
     * Before model save
     * @param \Rokanthemes\Blog\Model\Post $model
     * @param \Magento\Framework\App\Request\Http $request
     * @return void
     */
    protected function _beforeSave($model, $request)
    {
        $this->previousPosts = $model->getRelatedPosts();

        if ($links = $request->getParam('links')) {

            foreach (array('post', 'product') as $key) {
                $param = 'related' . $key . 's';
                if (!empty($links[$param])) {
                    $ids = array_unique(
                        array_map('intval',
                            explode('&', $links[$param])
                        )
                    );
                    if (count($ids)) {
                        $model->setData('related_' . $key . '_ids', $ids);
                    }
                }
            }
        }
    }

    /**
     * @param Post $model
     * @param RequestInterface $request
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    protected function _afterSave($model, $request)
    {
        $resourceModel = ObjectManager::getInstance()->create(\Rokanthemes\Blog\Model\ResourceModel\Post::class);
        if (is_array($request->getParam('in_products')))
            $model->setRelatedProductIds($request->getParam('in_products'));
        if (is_array($request->getParam('in_posts'))) {
            $model->setRelatedPostIds($request->getParam('in_posts'));
        }

        $resourceModel->save($model);
    }

}
