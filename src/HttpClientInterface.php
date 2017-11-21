<?php

namespace AvtoDev\MonetaApi;

interface HttpClientInterface
{
    /**
     * HttpClientInterface constructor.
     *
     * @param array $config
     */
    public function __construct($config);

    /**
     * @param $method
     * @param $uri
     * @param $options
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function request($method, $uri, $options);
}
