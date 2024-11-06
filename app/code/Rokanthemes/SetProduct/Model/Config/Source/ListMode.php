<?php
namespace Rokanthemes\SetProduct\Model\Config\Source;

class ListMode extends \Magento\Framework\App\Helper\AbstractHelper {

	public function toOptionArray(){
		return [
			['value' => '1', 'label' => __('Product 1')],
			['value' => '2', 'label' => __('Product 2')],
			['value' => '3', 'label' => __('Product 3')]
		];
	}
}
