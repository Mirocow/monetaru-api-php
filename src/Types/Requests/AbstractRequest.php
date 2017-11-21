<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\MonetaApi;
use AvtoDev\MonetaApi\Traits\FormatPhone;
use AvtoDev\MonetaApi\Traits\ConvertToCarbon;
use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;
use AvtoDev\MonetaApi\Exceptions\MonetaBadSettingsException;
use AvtoDev\MonetaApi\Exceptions\MonetaServerErrorException;

abstract class AbstractRequest
{
    use  ConvertToCarbon, FormatPhone;

    /**
     * @var AttributeCollection
     */
    protected $attributes;

    protected $version  = 'VERSION_2';

    protected $methodName;

    protected $required = [];

    /**
     * @var MonetaApi
     */
    protected $api;

    /**
     * AbstractRequest constructor.
     *
     * @param MonetaApi $api
     */
    public function __construct(MonetaApi $api)
    {
        $this->attributes = new AttributeCollection;
        $this->api        = $api;
    }

    public function toJson()
    {
        $this->checkRequired();

        $base = [
            'Envelope' => [
                'Header' => $this->api->getHeaders(),
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
        $response       = $this->api->apiRequest($this);
        $responseObject = \json_decode($response->getBody()->getContents());
        $responseBody   = $responseObject->Envelope->Body;
        if ($response->getStatusCode() !== 200) {
            throw $this->throwError($responseBody);
        }

        return $this->prepare($responseBody);
    }

    public function getAttributes()
    {
        return $this->attributes->copy();
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * Проверка обязательных к запонению аттрибутов.
     *
     * @throws MonetaBadRequestException
     */
    protected function checkRequired()
    {
        foreach ($this->required as $attribute) {
            if (! $this->attributes->hasByType($attribute)) {
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
