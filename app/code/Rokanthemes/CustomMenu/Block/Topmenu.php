<?php
namespace Rokanthemes\CustomMenu\Block;

class Topmenu extends \Magento\Framework\View\Element\Template
{

    protected $_categoryHelper;
    protected $_categoryFlatConfig;
    protected $_topMenu;
    protected $_categoryFactory;
    protected $_helper;
    protected $_filterProvider;
    protected $_blockFactory;
    protected $_custommenuConfig;
    protected $_storeManager;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Rokanthemes\CustomMenu\Helper\Data $helper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Theme\Block\Html\Topmenu $topMenu,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Cms\Model\BlockFactory $blockFactory
    ) {

        $this->_categoryHelper = $categoryHelper;
        $this->_categoryFlatConfig = $categoryFlatState;
        $this->_categoryFactory = $categoryFactory;
        $this->_topMenu = $topMenu;
        $this->_helper = $helper;
        $this->_filterProvider = $filterProvider;
        $this->_blockFactory = $blockFactory;
        $this->_storeManager = $context->getStoreManager();
        
        parent::__construct($context);
    }

    public function getCategoryHelper()
    {
        return $this->_categoryHelper;
    }

    public function getCategoryModel($id)
    {
        $_category = $this->_categoryFactory->create();
        $_category->load($id);
        
        return $_category;
    }
    
    public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
    {
        return $this->_topMenu->getHtml($outermostClass, $childrenWrapClass, $limit);
    }
    
    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }
    
    public function getChildCategories($category)
    {
        if ($this->_categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }
        
        return $subcategories;
    }
    
    public function getActiveChildCategories($category)
    {
        $children = [];
        if ($this->_categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }
        foreach($subcategories as $category) {
            if (!$category->getIsActive()) {
                continue;
            }
            $children[] = $category;
        }
        return $children;
    }
    
    public function getBlockContent($content = '') {
        if(!$this->_filterProvider)
            return $content;
        return $this->_filterProvider->getBlockFilter()->filter(trim($content));
    }
    
    public function getCustomBlockHtml($type='after') {
        $html = '';
        
        $block_ids = $this->_custommenuConfig['custom_links']['staticblock_'.$type];
        
        if (!$block_ids) return '';
        
        $block_ids = preg_replace('/\s/', '', $block_ids);
        $ids = explode(',', $block_ids);
        $store_id = $this->_storeManager->getStore()->getId();
        
        foreach($ids as $block_id) {
            $block = $this->_blockFactory->create();
            $block->setStoreId($store_id)->load($block_id);
            
            if(!$block) continue;
            
            $block_content = $block->getContent();
            
            if(!$block_content) continue;
            
            $content = $this->_filterProvider->getBlockFilter()->setStoreId($store_id)->filter($block_content);
            if(substr($content, 0, 4) == '<ul>')
                $content = substr($content, 4);
            if(substr($content, strlen($content) - 5) == '</ul>')
                $content = substr($content, 0, -5);

            $html .= $content;
        }
       
        return $html;
    }
    public function getSubmenuItemsHtml($children, $level = 1, $max_level = 0, $column_width=12, $menu_type = 'fullwidth', $columns = null)
    {
        $html = '';
        
        if(!$max_level || ($max_level && $max_level == 0) || ($max_level && $max_level > 0 && $max_level-1 >= $level)) {
            $column_class = "";
            if($level == 1 && $columns && ($menu_type == 'fullwidth' || $menu_type == 'staticwidth')) {
                $column_class = "col-sm-".$column_width." ";
                $column_class .= "mega-columns columns".$columns;
            }
            $html = '<ul class="subchildmenu '.$column_class.'">';
            foreach($children as $child) {
                $cat_model = $this->getCategoryModel($child->getId());
                
                $rt_menu_hide_item = $cat_model->getData('rt_menu_hide_item');
                
                if (!$rt_menu_hide_item) {
                    $sub_children = $this->getActiveChildCategories($child);
                    $custom_style = '';
                    $css_fixed = [];
                    if($cat_model->getData('rt_menu_bg_img') != ''){
                        $trim_pub = explode("media", $cat_model->getData('rt_menu_bg_img'));
                        $url_fixed = $cat_model->getData('rt_menu_bg_img');
                        if(is_array($trim_pub) && count($trim_pub) > 0){
                            $path_media = ltrim(end($trim_pub), '/');
                            $mediaUrl = $this ->_storeManager-> getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
                            $url_fixed = $mediaUrl.$path_media;
                        }

                        $css_fixed[] = "background-image: url('" .$url_fixed. "')";
                    }

                    if(!empty($css_fixed)){
                        $custom_style = 'style="'.implode("; ", $css_fixed).'"';
                    }
                    
                    $rt_menu_cat_label = $cat_model->getData('rt_menu_cat_label');
                    $rt_menu_icon_img = $cat_model->getData('rt_menu_icon_img');
                    $rt_menu_font_icon = $cat_model->getData('rt_menu_font_icon');

                    $item_class = 'level'.$level.' ';
                    if(count($sub_children) > 0)
                        $item_class .= 'parent ';
                    $html .= '<li class="ui-menu-item '.$item_class.'"'.$custom_style.'>';
                    if(count($sub_children) > 0) {
                        $html .= '<div class="open-children-toggle"></div>';
                    }
                    if($level == 1 && $rt_menu_icon_img) {
                        $html .= '<div class="menu-thumb-img"><a class="menu-thumb-link" href="'.$this->_categoryHelper->getCategoryUrl($child).'"><img src="' .$cat_model->getImageUrl('rt_menu_icon_img'). '" alt="'.$child->getName().'"/></a></div>';
                    }
                    $html .= '<a href="'.$this->_categoryHelper->getCategoryUrl($child).'">';
                    if ($level > 1 && $rt_menu_icon_img)
                        $html .= '<img class="menu-thumb-icon" src="' .$cat_model->getImageUrl('rt_menu_icon_img'). '" alt="'.$child->getName().'"/>';
                    elseif($rt_menu_font_icon)
                        $html .= '<em class="menu-thumb-icon '.$rt_menu_font_icon.'"></em>';
                    $html .= '<span>'.$child->getName();
                    if($rt_menu_cat_label)
                        $html .= '<span class="cat-label cat-label-'.$rt_menu_cat_label.'">'.$this->_custommenuConfig['cat_labels'][$rt_menu_cat_label].'</span>';
                    $html .= '</span></a>';
                    if(count($sub_children) > 0) {
                        $html .= $this->getSubmenuItemsHtml($sub_children, $level+1, $max_level, $column_width, $menu_type);
                    }
                    $html .= '</li>';
                }
            }
            $html .= '</ul>';
        }
        
        return $html;
    }
    
    public function getCustomMenuHtml()
    {
        $html = '';
        
        $categories = $this->getStoreCategories(true,false,true);
        
        $this->_custommenuConfig = $this->_helper->getConfig('custommenu');
        
        $max_level = $this->_custommenuConfig['general']['max_level'];
        $html .= $this->getCustomBlockHtml('before');
        foreach($categories as $category) {
            if (!$category->getIsActive()) {
                continue;
            }
            
            $cat_model = $this->getCategoryModel($category->getId());
            
            $rt_menu_hide_item = $cat_model->getData('rt_menu_hide_item');
            
            if(!$rt_menu_hide_item) {
                $children = $this->getActiveChildCategories($category);
                $rt_menu_cat_label = $cat_model->getData('rt_menu_cat_label');
                $rt_menu_icon_img = $cat_model->getData('rt_menu_icon_img');
                $rt_menu_font_icon = $cat_model->getData('rt_menu_font_icon');
                $rt_menu_cat_columns = $cat_model->getData('rt_menu_cat_columns');
                $rt_menu_float_type = $cat_model->getData('rt_menu_float_type');
                
                if(!$rt_menu_cat_columns){
                    $rt_menu_cat_columns = 4;
                }
                
                $menu_type = $cat_model->getData('rt_menu_type');
                if(!$menu_type)
                    $menu_type = $this->_custommenuConfig['general']['menu_type'];
                    
                $custom_style = '';
                $css_fixed = array();
                if($cat_model->getData('rt_menu_bg_img') != ''){
                       $trim_pub = explode("media", $cat_model->getData('rt_menu_bg_img'));
                    $url_fixed = $cat_model->getData('rt_menu_bg_img');
                    if(is_array($trim_pub) && count($trim_pub) > 0){
                        $path_media = ltrim(end($trim_pub), '/');
                        $mediaUrl = $this ->_storeManager-> getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
                        $url_fixed = $mediaUrl.$path_media;
                    }

                    $css_fixed[] = "background-image: url('" .$url_fixed. "')";
                }
                if($menu_type=="staticwidth")
                    $css_fixed[] = 'width: 500px';

                $rt_menu_static_width = $cat_model->getData('rt_menu_static_width');
                if($menu_type=="staticwidth" && $rt_menu_static_width)
                    $css_fixed[] = 'width: '.$rt_menu_static_width;

                if(!empty($css_fixed)){
                    $custom_style = 'style="'.implode("; ", $css_fixed).'"';
                }
                    
                $item_class = 'level0';
                $item_class .= ' '.$menu_type;
                
                $menu_top_content = $cat_model->getData('rt_menu_block_top_content');
                $menu_left_content = $cat_model->getData('rt_menu_block_left_content');
                $menu_left_width = $cat_model->getData('rt_menu_block_left_width');
                if(!$menu_left_content || !$menu_left_width)
                    $menu_left_width = 0;
                $menu_right_content = $cat_model->getData('rt_menu_block_right_content');
                $menu_right_width = $cat_model->getData('rt_menu_block_right_width');
                if(!$menu_right_content || !$menu_right_width)
                    $menu_right_width = 0;
                $menu_bottom_content = $cat_model->getData('rt_menu_block_bottom_content');
                if($rt_menu_float_type)
                    $rt_menu_float_type = 'fl-'.$rt_menu_float_type.' ';
                if(count($children) > 0){
                     $item_class .= ' menu-item-has-children';
                }
                if(count($children) > 0 || (($menu_type=="fullwidth" || $menu_type=="staticwidth") && ($menu_top_content || $menu_left_content || $menu_right_content || $menu_bottom_content)))
                    $item_class .= ' parent';
                $html .= '<li class="ui-menu-item '.$item_class.$rt_menu_float_type.'">';
                if(count($children) > 0) {
                    $html .= '<div class="open-children-toggle"></div>';
                }
                $html .= '<a href="'.$this->_categoryHelper->getCategoryUrl($category).'" class="level-top">';
                if ($rt_menu_icon_img)
                    $html .= '<img class="menu-thumb-icon" src="' . $cat_model->getImageUrl('rt_menu_icon_img') . '" alt="'.$category->getName().'"/>';
                elseif($rt_menu_font_icon)
                    $html .= '<em class="menu-thumb-icon '.$rt_menu_font_icon.'"></em>';
                $html .= $category->getName();
                if($rt_menu_cat_label)
                    $html .= '<span class="cat-label cat-label-'.$rt_menu_cat_label.'">'.$this->_custommenuConfig['cat_labels'][$rt_menu_cat_label].'</span>';
                $html .= '</a>';
                if(count($children) > 0 || (($menu_type=="fullwidth" || $menu_type=="staticwidth") && ($menu_top_content || $menu_left_content || $menu_right_content || $menu_bottom_content))) {
                    $html .= '<div class="level0 submenu"'.$custom_style.'>';
                    if(($menu_type=="fullwidth" || $menu_type=="staticwidth") && $menu_top_content) {
                        $html .= '<div class="menu-top-block">'.$this->getBlockContent($menu_top_content).'</div>';
                    }
                    if(count($children) > 0 || (($menu_type=="fullwidth" || $menu_type=="staticwidth") && ($menu_left_content || $menu_right_content))) {
                        $html .= '<div class="row">';
                        if(($menu_type=="fullwidth" || $menu_type=="staticwidth") && $menu_left_content && $menu_left_width > 0) {
                            $html .= '<div class="menu-left-block col-sm-'.$menu_left_width.'">'.$this->getBlockContent($menu_left_content).'</div>';
                        }
                        $html .= $this->getSubmenuItemsHtml($children, 1, $max_level, 12-$menu_left_width-$menu_right_width, $menu_type, $rt_menu_cat_columns);
                        if(($menu_type=="fullwidth" || $menu_type=="staticwidth") && $menu_right_content && $menu_right_width > 0) {
                            $html .= '<div class="menu-right-block col-sm-'.$menu_right_width.'">'.$this->getBlockContent($menu_right_content).'</div>';
                        }
                        $html .= '</div>';
                    }
                    if(($menu_type=="fullwidth" || $menu_type=="staticwidth") && $menu_bottom_content) {
                        $html .= '<div class="menu-bottom-block">'.$this->getBlockContent($menu_bottom_content).'</div>';
                    }
                    $html .= '</div>';
                }
                
                $html .= '</li>';
            }
        }
        $html .= $this->getCustomBlockHtml('after');
        
        return $html;
    }
}
