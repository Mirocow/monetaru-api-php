<?php

namespace AvtoDev\MonetaApi\HttpClients;

use GuzzleHttp\Client;

/**
 * Class GuzzleHttpClient.
 *
 * Http-клинт основаный на библиотеке Guzzle
 *
 * @see Client
 */
class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * Инстанс Http-клиента.
     *
     * @var Client
     */
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
