<?php

namespace AvtoDev\MonetaApi\Clients;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use AvtoDev\MonetaApi\HttpClients\GuzzleHttpClient;
use AvtoDev\MonetaApi\Types\Requests\AbstractRequest;
use AvtoDev\MonetaApi\HttpClients\HttpClientInterface;
use AvtoDev\MonetaApi\Traits\StackValuesDotAccessible;
use AvtoDev\MonetaApi\Clients\ApiCommands\FinesApiCommands;
use AvtoDev\MonetaApi\Exceptions\MonetaBadSettingsException;
use AvtoDev\MonetaApi\Clients\ApiCommands\PaymentsApiCommands;
use AvtoDev\MonetaApi\Clients\ApiCommands\ServiceProviderApiCommands;

class MonetaApi
{
    use StackValuesDotAccessible {
        getStackValueWithDot as getConfigValue;
    }

    /**
     * Массив настроек.
     *
     * @var array
     */
    protected $config   = [
        /*
         * Endpoint работы с Монетой.
         */
        'endpoint'         => 'https://service.moneta.ru:51443/services',

        /*
         * ИД ГБДД в системе Монета.
         */
        'fine_provider_id' => '9171.1',
        'accounts'         => [
            //Счёт гибдд
            'provider'   => [
                'id'     => '9171',
                'sub_id' => '1',
            ],
            'fines'      => [
                'id'       => '',
                'password' => '',

            ],
            'commission' => [
                'id'       => '',
                'password' => '',
            ],
            //плательщик карта
            'payer_card' => '303',
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
        'accounts.fines.id',
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
     * @var PaymentsApiCommands
     */
    protected $paymentsCommanderClass;

    /**
     * @var FinesApiCommands
     */
    protected $finesCommanderClass;

    /**
     * @var ServiceProviderApiCommands
     */
    protected $serviceProviderCommanderClass;

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
     * Методы работы с провайдерами.
     *
     * @return ServiceProviderApiCommands
     */
    public function serviceProvider()
    {
        if (! $this->serviceProviderCommanderClass) {
            $this->serviceProviderCommanderClass = new ServiceProviderApiCommands($this);
        }

        return $this->serviceProviderCommanderClass;
    }

    /**
     * Методы работы со штрафами.
     *
     * @return FinesApiCommands
     */
    public function fines()
    {
        if (! isset($this->finesCommanderClass) || ! ($this->finesCommanderClass instanceof FinesApiCommands)) {
            $this->finesCommanderClass = new FinesApiCommands($this);
        }

        return $this->finesCommanderClass;
    }

    /**
     * Методы работы с финансами.
     *
     * @return PaymentsApiCommands
     */
    public function payments()
    {
        if (! isset($this->paymentsCommanderClass)
            || ! ($this->paymentsCommanderClass instanceof PaymentsApiCommands)
        ) {
            $this->paymentsCommanderClass = new PaymentsApiCommands($this);
        }

        return $this->paymentsCommanderClass;
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
     * Возвращает работает ли клиент в тестовом режиме.
     *
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
        if (file_exists($path = __DIR__ . "/../TestResponses/$methodName.json")) {
            $json = file_get_contents($path);
        } else {
            $json = file_get_contents(__DIR__ . '/../TestResponses/ResponseStructure.json');
        }

        return $json;
    }

    /**
     * Генерирует авторизационные заголовки.
     *
     * @param string $userName
     * @param string $password
     *
     * @return array
     */
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

    /**
     * Проверяет заполнены ли все необходимые настройки.
     *
     * @throws MonetaBadSettingsException
     */
    protected function checkSettings()
    {
        if ($this->isTest()) {
            return;
        }
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
     * Инициализирует http-клиент
     *
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
