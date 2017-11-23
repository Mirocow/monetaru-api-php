<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Clients\MonetaApi;
use AvtoDev\MonetaApi\Traits\FormatPhone;
use AvtoDev\MonetaApi\Traits\CheckRequired;
use AvtoDev\MonetaApi\Traits\ConvertToArray;
use AvtoDev\MonetaApi\Traits\ConvertToCarbon;
use AvtoDev\MonetaApi\Support\Contracts\Jsonable;
use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;
use AvtoDev\MonetaApi\Exceptions\MonetaBadSettingsException;
use AvtoDev\MonetaApi\Exceptions\MonetaServerErrorException;

abstract class AbstractRequest implements Jsonable
{
    use  ConvertToArray, ConvertToCarbon, FormatPhone, CheckRequired;

    /**
     * @var AttributeCollection
     */
    protected $attributes;

    /**
     * Версия апи.
     *
     * @var string
     */
    protected $version = 'VERSION_2';

    /**
     * Название метода.
     *
     * @var string
     */
    protected $methodName;

    /**
     * Поля обязательные к заполнению.
     *
     * @var array
     */
    protected $required = [];

    /**
     * Инстанс api-клиента.
     *
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

    /**
     * Составляет Json тело запроса
     * {@inheritdoc}
     */
    public function toJson($options = 0)
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

        return json_encode($base, $options);
    }

    /**
     * Подготавливает ответ
     *
     * @param \stdClass $response
     */
    abstract public function prepare($response);

    /**
     * Выполняет запрос
     *
     * @throws MonetaBadRequestException
     * @throws MonetaBadSettingsException
     * @throws MonetaServerErrorException
     *
     * @return mixed
     */
    public function exec()
    {
        $this->checkRequired();

        $response       = $this->api->apiRequest($this);
        $responseObject = json_decode($response->getBody()->getContents());
        $responseBody   = $responseObject->Envelope->Body;
        if ($response->getStatusCode() !== 200 || isset($responseBody->fault)) {
            throw $this->prepareError($responseBody);
        }

        return $this->prepare($responseBody);
    }

    /**
     * Получает копию аттрибутов запроса.
     *
     * @return AttributeCollection
     */
    public function getAttributes()
    {
        return $this->attributes->copy();
    }

    /**
     * Возвращает название метода.
     *
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * Строит тело запроса.
     *
     * @return array
     */
    abstract protected function createBody();

    /**
     * Подготавливает ошибку.
     *
     * @param \stdClass $response
     *
     * @return MonetaBadRequestException|MonetaBadSettingsException|MonetaServerErrorException
     */
    protected function prepareError($response)
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

    /**
     * {@inheritdoc}
     */
    protected function getCollection()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequiredReference()
    {
        return $this->required;
    }
}
