<?php

namespace Uzer\Middleware\Model;


use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Webapi\Rest\Request;
use Uzer\Middleware\Helper\Configuration;
use Uzer\Middleware\Logger\Logger;

class MiddlewareAuth implements \Uzer\Core\Api\MiddlewareAuthInterface
{

    protected ApiRequest $apiRequest;
    protected Configuration $configuration;

    /**
     * @param ApiRequest $apiRequest
     * @param Configuration $configuration
     */
    public function __construct(ApiRequest $apiRequest, Configuration $configuration)
    {
        $this->apiRequest = $apiRequest;
        $this->configuration = $configuration;
    }


    /**
     * @param array $data
     * @return false|mixed
     * @throws GuzzleException
     */
    public function auth(array $data = [])
    {
        $requestData = [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'client_id' => $this->configuration->getClientId(),
                'client_secret' => $this->configuration->getClientSecret()
            ]
        ];
        $response = $this->apiRequest->doRequest('token', $requestData, Request::HTTP_METHOD_POST);
        if ($response->getStatusCode() == 200) {
            $authorization = $response->getHeader('Authorization');
            return reset($authorization);
        } else {
            throw new \RuntimeException($response->getStatusCode());
        }
    }
}
