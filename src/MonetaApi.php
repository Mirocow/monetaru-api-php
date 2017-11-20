<?php

namespace AvtoDev\MonetaApi;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Types\Requests\FinesRequest;
use AvtoDev\MonetaApi\HttpClients\GuzzleHttpClient;
use AvtoDev\MonetaApi\Types\Requests\PaymentRequest;
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
            'provider_account'   => '9171',
            'fines_account'      => '',
            'commission_account' => '',
        ],
        'authorization'    => [
            'username' => '',
            'password' => '',
        ],
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
     * @param array                    $config
     * @param HttpClientInterface|null $httpClient
     */
    public function __construct(
        $config = [],
        HttpClientInterface $httpClient = null)
    {
        $this->config = array_replace_recursive($this->config, $config);
        $this->checkSettings();
        if (! $httpClient) {
            $httpClient = new GuzzleHttpClient($this->getConfigValue('endpoint'));
        }

        $this->httpClient = $httpClient;

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
        $request = new FinesRequest($this->httpClient, $this->inputHeaders, $this->getConfigValue('fine_provider_id'));

        return $request;
    }

    public function getServiceProvider()
    {
        $findProviderRequest = new FindServiceProviderByIdRequest($this->httpClient, $this->inputHeaders, '');

        return $findProviderRequest;
    }

    /**
     * @param Fine $fine
     *
     * @return PaymentRequest
     */
    public function payRequest(Fine $fine)
    {
        $request = new PaymentRequest($this->httpClient, $this->inputHeaders,
            $this->getConfigValue('accounts.provider_account'),
            $fine);
        $request->setAccountNumber($this->getConfigValue('accounts.fines_account'));

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
}
