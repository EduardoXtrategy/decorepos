<?php

namespace Uzer\Infor\Model\Api;


use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Uzer\Infor\Helper\Data as AuthData;

class Auth
{

    protected ClientFactory $client;
    protected AuthData $authData;

    public function __construct(
        ClientFactory $client,
        AuthData      $authData
    )
    {
        $this->client = $client;
        $this->authData = $authData;
    }


    /**
     * @throws GuzzleException
     */
    public function getBearerToken($storeId = null)
    {
        $client = $this->client->create();
        $basic = base64_encode($this->authData->getClientId($storeId) . ':' . $this->authData->getClientSecret($storeId));
        $response = $client->request('POST', $this->authData->getAccessTokenUrl($storeId), [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => sprintf('Basic %s', $basic)
            ],
            'form_params' => [
                'grant_type' => 'password',
                'username' => $this->authData->getUsername($storeId),
                'password' => $this->authData->getPassword($storeId),
            ]
        ]);

        $response = json_decode($response->getBody()->getContents(), true);
        return $response['access_token'];
    }

}
