<?php

namespace AvtoDev\MonetaApi;

use GuzzleHttp\Psr7\Response;
use AvtoDev\MonetaApi\Types\Fine;
use Psr\Http\Message\ResponseInterface;
use AvtoDev\MonetaApi\Types\Requests\FinesRequest;
use AvtoDev\MonetaApi\HttpClients\GuzzleHttpClient;
use AvtoDev\MonetaApi\Types\Requests\PaymentRequest;
use AvtoDev\MonetaApi\Types\Requests\AbstractRequest;
use AvtoDev\MonetaApi\Traits\StackValuesDotAccessible;
use AvtoDev\MonetaApi\Exceptions\MonetaBadSettingsException;
use AvtoDev\MonetaApi\Types\Requests\FindServiceProviderByIdRequest;

class MonetaApi
{
    use StackValuesDotAccessible {
        getStackValueWithDot as getConfigValue;
    }

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
    protected $config   = [
        /**
         * Endpoint работы с Монетой.
         */
        'endpoint'         => 'https://service.moneta.ru:51443/services',

        /**
         * ИД ГБДД в системе Монета.
         */
        'fine_provider_id' => '9171.1',
        'accounts'         => [
            'provider'           => [
                'id'     => '9171',
                'sud_id' => '1',
            ],
            'fines_account'      => '',
            'commission_account' => '',
        ],
        'authorization'    => [
            'username' => '',
            'password' => '',
        ],
        'http_clients'     => [
            'guzzle' => [
                'headers'     => [
                    'Content-Type' => 'application/json;charset=UTF-8',
                ],
                'timeout'     => 30,
                'http_errors' => false,
            ],
        ],
        'use_http_client'  => 'guzzle',
        'is_test'          => false,
    ];

    protected $required = [
        'authorization.username',
        'authorization.password',
        'accounts.fines_account',
    ];

    /**
     * Клиент сервиса штрафов.
     *
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * Загаловки запроса.
     *
     * @var array
     */
    protected $inputHeaders;

    /**
     * MonetaApi constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = array_replace_recursive($this->config, $config);
        $this->checkSettings();

        $this->httpClient = $this->httpClientFactory();

        $this->inputHeaders = $this->createSecurityHeader(
            $this->getConfigValue('authorization.username'),
            $this->getConfigValue('authorization.password')
        );
    }

    /**
     * Инициализация поиска.
     *
     * @return FinesRequest
     */
    public function findFines()
    {
        $request = new FinesRequest($this);

        return $request;
    }

    public function getServiceProvider()
    {
        $findProviderRequest = new FindServiceProviderByIdRequest($this);

        return $findProviderRequest;
    }

    /**
     * @param Fine $fine
     *
     * @return PaymentRequest
     */
    public function payRequest(Fine $fine)
    {
        $request = new PaymentRequest($this, $fine);

        return $request;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->inputHeaders;
    }

    /**
     * @param AbstractRequest $request
     *
     * @return ResponseInterface
     */
    public function apiRequest(AbstractRequest $request)
    {
        $response = null;
        if ($this->isTest()) {
            $response = new Response(200, [], $this->findTestResponse($request->getMethodName()));
        } else {
            $response = $this->httpClient->request(
                'POST',
                $this->getConfigValue('endpoint'),
                ['body' => $request->toJson()]
            );
        }

        return $response;
    }

    /**
     * @return bool
     */
    public function isTest()
    {
        return $this->getConfigValue('is_test');
    }

    /**
     * @param string $methodName
     *
     * @return string
     */
    protected function findTestResponse($methodName)
    {
        $json = file_get_contents(__DIR__ . "/TestResponses/$methodName.json");
        if (! $json) {
            $json = '{}';
        }

        return $json;
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

    protected function checkSettings()
    {
        foreach ($this->required as $configItem) {
            if (! $this->getConfigValue($configItem)) {
                throw new MonetaBadSettingsException("Не заполнен обязательный параметр $configItem");
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getAccessorStack()
    {
        return $this->config;
    }

    /**
     * @throws MonetaBadSettingsException
     *
     * @return HttpClientInterface
     */
    protected function httpClientFactory()
    {
        $client         = $this->getConfigValue('use_http_client');
        $clientSettings = $this->getConfigValue('http_clients.' . $client);
        switch ($client) {
            case 'guzzle':
                $httpClient = new GuzzleHttpClient($clientSettings);
                break;
            default:
                throw new MonetaBadSettingsException('Данный вид http клиента не поддерживается');
        }

        return $httpClient;
    }
}
