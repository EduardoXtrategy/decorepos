<?php

namespace Uzer\Middleware\Model;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use Magento\Framework\Webapi\Rest\Request;
use Uzer\Middleware\Helper\Configuration;

class ApiRequest
{
    protected ResponseFactory $responseFactory;
    protected ClientFactory $clientFactory;
    protected Configuration $configuration;

    /**
     * @param ResponseFactory $responseFactory
     * @param ClientFactory $clientFactory
     * @param Configuration $configuration
     */
    public function __construct(
        ResponseFactory $responseFactory,
        ClientFactory   $clientFactory,
        Configuration   $configuration
    )
    {
        $this->responseFactory = $responseFactory;
        $this->clientFactory = $clientFactory;
        $this->configuration = $configuration;
    }

    /**
     * @param string $uriEndpoint
     * @param array $params
     * @param string $requestMethod
     * @return Response
     * @throws GuzzleException
     */
    public function doRequest(string $uriEndpoint, array $params = [], string $requestMethod = Request::HTTP_METHOD_GET): Response
    {
        $url = $this->configuration->getUrl();
        $client = $this->clientFactory->create(['config' => ['base_uri' => $url]]);
        return $client->request($requestMethod, $uriEndpoint, $params);
    }

}
