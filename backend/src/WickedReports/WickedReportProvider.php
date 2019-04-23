<?php

namespace App\WickedReports;

use GuzzleHttp\Client;
use Psr\Log\LoggerAwareTrait;

class WickedReportProvider
{
    use LoggerAwareTrait;

    private const API_URL = 'https://api.wickedreports.com/';
    private const API_TOKEN = 'F76AahJFyq7NC25jSjQ4mO2twEXddmhO';

    /**
     * @param array $contacts
     *
     * @return bool
     */
    public function storeContacts(array $contacts): bool
    {
        return true;
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
}
