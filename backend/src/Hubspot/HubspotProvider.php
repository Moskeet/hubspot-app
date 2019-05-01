<?php

namespace App\Hubspot;

use App\Entity\HubspotToken;
use GuzzleHttp\Client;
use Psr\Log\LoggerAwareTrait;

class HubspotProvider
{
    use LoggerAwareTrait;

    private const API_URL = 'https://api.hubapi.com/';
    private const CONTACTS_PER_CALL = 1;

    /**
     * @var string
     */
    private $apiId;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * @param string $apiId
     * @param string $apiSecret
     */
    public function __construct(
        string $apiId,
        string $apiSecret
    ) {
        $this->apiId = $apiId;
        $this->apiSecret = $apiSecret;
    }

    /**
     * @param HubspotToken $hubspotToken
     */
    public function getTokenByCode(HubspotToken $hubspotToken): void
    {
        $response = $this->request(
            'POST',
            'oauth/v1/token',
            [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->apiId,
                    'client_secret' => $this->apiSecret,
                    'redirect_uri' => $hubspotToken->getRedirectUri(),
                    'code' => $hubspotToken->getCode(),
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            return;
        }

        $body = json_decode((string)$response->getBody(), true);
        $this->updateTokenProperties(
            $hubspotToken,
            $body['access_token'],
            $body['refresh_token'],
            $body['expires_in']
        );
    }

    /**
     * @param HubspotToken $hubspotToken
     *
     * @return bool
     */
    public function refreshToken(HubspotToken $hubspotToken): bool
    {
        $response = $this->request(
            'POST',
            'oauth/v1/token',
            [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->apiId,
                    'client_secret' => $this->apiSecret,
                    'refresh_token' => $hubspotToken->getRefreshToken(),
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $body = json_decode((string)$response->getBody(), true);
        $this->updateTokenProperties(
            $hubspotToken,
            $body['access_token'],
            $body['refresh_token'],
            $body['expires_in']
        );

        return true;
    }

    /**
     * @param HubspotToken $hubspotToken
     *
     * @return array|null
     */
    public function fetchContacts(HubspotToken $hubspotToken): ?array
    {
        $parameters = [
            'count' => self::CONTACTS_PER_CALL,
            'propertyMode' => 'value_only',
            'formSubmissionMode' => 'oldest',
        ];

        if ($hubspotToken->getTimeOffset()) {
            $parameters['timeOffset'] = $hubspotToken->getTimeOffset();
        }

        $response = $this->request(
            'GET',
            'contacts/v1/lists/all/contacts/recent?' . http_build_query($parameters),
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $hubspotToken->getToken(),
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * @param $method
     * @param string $uri
     * @param array $options
     *
     * @return null|mixed|\Psr\Http\Message\ResponseInterface
     */
    private function request($method, $uri = '', array $options = [])
    {
        $client = new Client();

        try {
            $response = $client->request($method, self::API_URL . $uri, $options);
        } catch (\Exception $e) {
            $this->logger->error('Status unknown', [
                'message' => $e->getMessage(),
            ]);
        }

        $body = json_decode((string)$response->getBody(), true);
        $this->logger->debug(
            sprintf('Status: %s, Method: %s, Url: %s ', $response->getStatusCode(), $method, $uri),
            [
                'response' => $body,
            ]
        );

        return $response;
    }

    /**
     * @param HubspotToken $hubspotToken
     * @param string $accessToken
     * @param string $refreshToken
     * @param int $expiresIn
     *
     * @return HubspotToken
     */
    private function updateTokenProperties(
        HubspotToken $hubspotToken,
        string $accessToken,
        string $refreshToken,
        int $expiresIn): HubspotToken
    {
        return $hubspotToken
            ->setToken($accessToken)
            ->setRefreshToken($refreshToken)
            ->setRefreshDatetime(new \DateTime('+'.$expiresIn.' seconds'))
        ;
    }
}
