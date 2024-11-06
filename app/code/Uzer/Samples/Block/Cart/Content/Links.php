<?php

namespace Uzer\Samples\Block\Cart\Content;

use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Element\Template;
use Uzer\Samples\Block\Cart\BaseBlock;
use Uzer\Samples\Model\Session;

class Links extends BaseBlock
{

    private Session $session;
    protected SessionManagerInterface $genericSession;
    protected Session\FilterSession $filterSession;

    public function __construct(
        Template\Context        $context,
        CurrencyFactory         $currencyFactory,
        Session                 $session,
        SessionManagerInterface $genericSession,
        Session\FilterSession   $filterSession,
        array                   $data = []
    )
    {
        parent::__construct($context, $currencyFactory, $data);
        $this->session = $session;
        $this->genericSession = $genericSession;
        $this->filterSession = $filterSession;
    }


    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\SessionException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function hasItems(): bool
    {
        if (!$this->session->hasSamplesCart()) {
            return false;
        }
        return count($this->session->getSamplesCart()->getItems()) > 0;
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrlContinue()
    {
        if ($this->filterSession->isSessionExists()) {
            $url = $this->filterSession->getUrlFilter($this->_storeManager->getStore()->getCode());
            if ($url) {
                $parseUrl = parse_url(str_replace('&?', '', $url));
                if (isset($parseUrl['query'])) {
                    $items = array();
                    parse_str($parseUrl['query'], $items);
                    unset($items['p']);
                    unset($items['is_scroll']);
                    $parseUrl['query'] = '?' . http_build_query($items);
                } else {
                    $parseUrl['query'] = '';
                }
                return sprintf('%s://%s%s%s', $parseUrl['scheme'], $parseUrl['host'], $parseUrl['path'], $parseUrl['query']);
            }
        }
        return $this->getUrl('all-products');
    }
}
