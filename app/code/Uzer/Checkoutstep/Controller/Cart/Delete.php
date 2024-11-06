<?php
namespace Uzer\Checkoutstep\Controller\Cart;

class Delete extends \Magento\Checkout\Controller\Cart\Delete
{

    /**
     * Delete shopping cart item action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if (! $this->_formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
        $id = (int) $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $item = $this->cart->getQuote()->getItemById($id);
                $name = $item->getName();
                $this->cart->removeItem($id);
                // We should set Totals to be recollected once more because of Cart model as usually is loading
                // before action executing and in case when triggerRecollect setted as true recollecting will
                // executed and the flag will be true already.
                $this->cart->getQuote()->setTotalsCollectedFlag(false);
                $this->cart->save();
                $this->messageManager->addSuccessMessage(__('The product %1 has been removed', [
                    $name
                ]));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t remove the item.'));
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
            }
        }
        $defaultUrl = $this->_objectManager->create(\Magento\Framework\UrlInterface::class)->getUrl('*/*');
        return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRedirectUrl($defaultUrl));
    }
}

