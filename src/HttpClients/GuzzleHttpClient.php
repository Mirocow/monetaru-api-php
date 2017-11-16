<?php

namespace AvtoDev\MonetaApi\HttpClients;

use GuzzleHttp\Client;
use AvtoDev\MonetaApi\HttpClientInterface;

class GuzzleHttpClient implements HttpClientInterface
{
    protected $client;

    protected $endpoint;

    protected $_lastStatusCode = 0;

    /**
     * {@inheritdoc}
     */
    public function __construct($url)
    {
        $this->endpoint = $url;
        $this->client   = new Client([
            'headers' => ['Content-Type' => 'application/json;charset=UTF-8'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function post($json)
    {
        $response = $this->client->post($this->endpoint, [
            'body'        => $json,
            'timeout'     => 30,
            'http_errors' => false,
        ]);

        $this->_lastStatusCode = $response->getStatusCode();

        return $response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function lastStatusCode()
    {
        return $this->_lastStatusCode;
    }
}
