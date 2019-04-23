<?php

namespace App\WickedReports;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerAwareTrait;

class WickedReportProvider
{
    use LoggerAwareTrait;

    private const API_URL = 'https://api.wickedreports.com/';
    private const API_TOKEN = 'F76AahJFyq7NC25jSjQ4mO2twEXddmhO';
    private const TEST_MODE = true;

    /**
     * @param array $contacts
     *
     * @return bool
     */
    public function storeContacts(array $contacts): bool
    {
        $response = $this->request(
            'POST',
            'contacts',
            [
                RequestOptions::JSON => $contacts,
            ]
        );

        return $response->getStatusCode() === 200;
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
        $extraHeaders = [
            'headers' => [
                'apikey' => self::API_TOKEN,
            ],
        ];

        if (self::TEST_MODE) {
            $extraHeaders['headers']['test'] = 1;
        }

        $options = array_merge_recursive($options, $extraHeaders);

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
}
