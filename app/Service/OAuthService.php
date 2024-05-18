<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OAuthService
{
    private $client;
    private $tokenUrl;
    private $clientId;
    private $clientSecret;
    private $scope;

    public function __construct()
    {
        $this->client = new Client();
        $this->tokenUrl = config('services.oauth.token_url');
        $this->clientId = config('services.oauth.client_id');
        $this->clientSecret = config('services.oauth.client_secret');
        $this->scope = config('services.oauth.scope');
    }

    public function getAccessToken()
    {
        try {
            $response = $this->client->post(str_replace('{tenant_id}', config('services.oauth.tenant_id'), $this->tokenUrl), [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope' => $this->scope,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['access_token'];
        } catch (GuzzleException $e) {
            throw new \Exception('Unable to retrieve access token: ' . $e->getMessage());
        }
    }
}
