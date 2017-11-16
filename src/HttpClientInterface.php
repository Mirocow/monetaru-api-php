<?php

namespace AvtoDev\MonetaApi;

interface HttpClientInterface
{
    /**
     * HttpClientInterface constructor.
     *
     * @param string $url endpoint url
     */
    public function __construct($url);

    /**
     * Send post request.
     *
     * @param string $json json request
     *
     * @return mixed
     */
    public function post($json);

    /**
     * Return request status code.
     *
     * @return int
     */
    public function lastStatusCode();
}
