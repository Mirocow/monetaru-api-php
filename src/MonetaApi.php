<?php

namespace AvtoDev\MonetaApi;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Types\Requests\FinesRequest;
use AvtoDev\MonetaApi\HttpClients\GuzzleHttpClient;
use AvtoDev\MonetaApi\Types\Requests\PaymentRequest;
use AvtoDev\MonetaApi\Types\Requests\FindServiceProviderByIdRequest;

class MonetaApi
{
    /**
     * ИД ГБДД в системе Монета.
     *
     * @var string
     */
    public $fineProviderId = '9171.1';

    /**
     * Массив настроек.
     *
     * @var array
     */
    protected $config = [
        'endpoint' => 'https://service.moneta.ru:51443/services',
    ];

    /**
     * Клиент сервиса штрафов.
     *
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * Версия API.
     *
     * @var string
     */
    protected $version = 'VERSION_3';

    /**
     * Загаловки запроса.
     *
     * @var array
     */
    protected $inputHeaders;

    public function __construct($username, $password, $endpoint = '', HttpClientInterface $httpClient = null)
    {
        if ($endpoint) {
            $this->config['endpoint'] = $endpoint;
        }
        if (! $httpClient) {
            $httpClient = new GuzzleHttpClient($this->config['endpoint']);
        }
        $this->httpClient = $httpClient;

        $this->inputHeaders = $this->createSecurityHeader($username, $password);
    }

    /**
     * Инициализация поиска.
     *
     * @return FinesRequest
     */
    public function findFines()
    {
        $request = new FinesRequest($this->httpClient, $this->inputHeaders, $this->fineProviderId);

        return $request;
    }

    public function getServiceProvider()
    {
        $findProviderRequest = new FindServiceProviderByIdRequest($this->httpClient, $this->inputHeaders, '');

        return $findProviderRequest;
    }

    /**
     * @param Fine       $fine
     * @param int|string $accountNumber
     *
     * @return PaymentRequest
     */
    public function payRequest(Fine $fine, $accountNumber)
    {
        $request = new PaymentRequest($this->httpClient, $this->inputHeaders, $this->fineProviderId, $fine);
        $request->setAccountNumber($accountNumber);

        return $request;
    }

    protected function createSecurityHeader($userName, $password)
    {
        $header = [
            'Security' => [
                'UsernameToken' => [
                    'Username' => $userName,
                    'Password' => $password,
                ],
            ],
        ];

        return $header;
    }
}
