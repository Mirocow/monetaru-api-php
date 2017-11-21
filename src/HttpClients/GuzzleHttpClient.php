<?php

namespace AvtoDev\MonetaApi\HttpClients;

use GuzzleHttp\Client;
use AvtoDev\MonetaApi\HttpClientInterface;

class GuzzleHttpClient implements HttpClientInterface
{
    protected $client;

    /**
     * {@inheritdoc}
     */
    public function __construct($config)
    {
        $this->client = new Client($config);
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $uri, $options)
    {
        return $this->client->request($method, $uri, $options);
    }
}
