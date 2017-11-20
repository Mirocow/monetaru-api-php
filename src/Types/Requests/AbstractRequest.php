<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\HttpClientInterface;
use AvtoDev\MonetaApi\Traits\HasAttributes;
use AvtoDev\MonetaApi\Traits\ConvertToCarbon;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;
use AvtoDev\MonetaApi\Exceptions\MonetaBadSettingsException;
use AvtoDev\MonetaApi\Exceptions\MonetaServerErrorException;

abstract class AbstractRequest
{
    use HasAttributes, ConvertToCarbon;

    protected $header;

    protected $version = 'VERSION_2';

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    protected $methodName = '';

    protected $providerId;

    protected $required   = [];

    /**
     * AbstractRequest constructor.
     *
     * @param HttpClientInterface $httpClient
     * @param array               $header     Заголовки авторизации
     * @param string              $providerId
     */
    public function __construct(HttpClientInterface $httpClient, $header, $providerId)
    {
        $this->httpClient = $httpClient;
        $this->providerId = $providerId;
        $this->header     = $header;
    }

    public function getJson()
    {
        $base = [
            'Envelope' => [
                'Header' => $this->header,
                'Body'   => [
                    $this->methodName => $this->createBody(),
                ],
            ],
        ];

        return \json_encode($base);
    }

    abstract public function prepare($response);

    public function exec()
    {
        $this->checkRequired();
        $response = $this->httpClient->post($this->getJson());

        $responseObject = \json_decode($response);
        $responseBody   = $responseObject->Envelope->Body;
        if ($this->httpClient->lastStatusCode() !== 200 || isset($responseBody->fault)) {
            throw $this->throwError($responseBody);
        }

        return $this->prepare($responseBody);
    }

    protected function checkRequired()
    {
        foreach ($this->required as $attribute) {
            if (! $this->hasAttributeByType($attribute)) {
                throw new MonetaBadRequestException("Не заполнен обязательный атрибут: $attribute", '500.1');
            }
        }
    }

    abstract protected function createBody();

    protected function throwError($response)
    {
        $exception = new MonetaBadSettingsException;
        $message   = '';
        if (isset($response->fault->faultstring) && trim($response->fault->faultstring)) {
            $message = $response->fault->faultstring;
        }
        switch ($response->fault->faultcode) {
            case 'Client':
                $exception = new MonetaBadRequestException($message, $response->fault->detail->faultDetail);
                break;
            case 'Server':
                $exception = new MonetaServerErrorException($message, $response->fault->detail->faultDetail);
                break;
        }

        return $exception;
    }
}
