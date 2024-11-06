<?php


namespace Uzer\Newsletter\Plugin\Plugin;

use Magento\Framework\App\Response\Http as responseHttp;
use Magento\Framework\UrlInterface;

class Redirect
{
    private responseHttp $response;
    private UrlInterface $_url;

    /**
     * Redirect constructor.
     * @param responseHttp $response
     * @param UrlInterface $url
     */
    public function __construct(
        responseHttp $response,
        UrlInterface $url
    )
    {
        $this->response = $response;
        $this->_url = $url;
    }

    /**
     * @param \Magento\Newsletter\Controller\Subscriber\NewAction $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(\Magento\Newsletter\Controller\Subscriber\NewAction $subject, $result)
    {
        $url = $this->_url->getUrl('newsletter/registration/success');
        $this->response->setRedirect($url);
        return $result;
    }
}
