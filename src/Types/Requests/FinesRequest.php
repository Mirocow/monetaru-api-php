<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use Carbon\Carbon;
use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Support\FineCollection;
use AvtoDev\MonetaApi\References\FineReference;
use AvtoDev\MonetaApi\References\FinesRequestReference;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

/**
 * Class FinesRequest.
 *
 * Запрос поиска штрафов
 *
 * @see FinesRequestReference
 */
class FinesRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected $version = 'VERSION_3';

    /**
     * {@inheritdoc}
     */
    protected $methodName = 'GetNextStepRequest';

    /**
     * {@inheritdoc}
     */
    protected $responseName = 'GetNextStepResponse';

    /**
     * {@inheritdoc}
     */
    protected $required = [
        FinesRequestReference::SEARCH_METHOD,
    ];

    /**
     * Устанавливает номер свидетельства о регистрации ТС.
     *
     * @param string $sts
     *
     * @return $this
     */
    public function bySTS($sts)
    {
        $this->attributes->push(new MonetaAttribute(
            FinesRequestReference::SEARCH_METHOD,
            FinesRequestReference::SEARCH_METHOD_PERSONAL
        ));
        $this->attributes->push(new MonetaAttribute(FinesRequestReference::SEARCH_BY_STS, (string) trim($sts)));

        return $this;
    }

    /**
     * Устанавливает уникальный идентификатор начисления.
     * Можно указать через запятую, либо передать массив.
     *
     * @param array|string $uin
     *
     * @return $this
     */
    public function byUin($uin)
    {
        if (is_array($uin)) {
            $newUin = '';
            foreach ($uin as $id) {
                $newUin .= $id . ',';
            }
            $uin = mb_substr($newUin, 0, -1);
        }
        $this->attributes->push(new MonetaAttribute(
            FinesRequestReference::SEARCH_METHOD,
            FinesRequestReference::SEARCH_METHOD_UIN
        ));
        $this->attributes->push(new MonetaAttribute(FinesRequestReference::SEARCH_BY_UIN, (string) trim($uin)));

        return $this;
    }

    /**
     * Устанавливает номер прав.
     *
     * @param string $driverLicense
     *
     * @return $this
     */
    public function byDriverLicense($driverLicense)
    {
        $this->attributes->push(new MonetaAttribute(
            FinesRequestReference::SEARCH_METHOD,
            FinesRequestReference::SEARCH_METHOD_PERSONAL
        ));
        $this->attributes->push(
            new MonetaAttribute(FinesRequestReference::SEARCH_BY_DRIVE_LICENCE, (string) trim($driverLicense))
        );

        return $this;
    }

    /**
     * Включая оплаченные.
     *
     * @return $this
     */
    public function includePaid()
    {
        $this->attributes->push(new MonetaAttribute(
            FinesRequestReference::CHARGE_STATUS,
            FinesRequestReference::CHARGE_STATUS_BOTH
        ));

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return Fine[]|FineCollection
     */
    public function exec()
    {
        return parent::exec();
    }

    /**
     * Устанавливает начальную дату поиска.
     *
     * @param Carbon|\DateTime|int|string $date_time
     *
     * @return $this
     */
    public function dateFrom($date_time)
    {
        $carbon = $this->convertToCarbon($date_time);
        $this->attributes->push(
            new MonetaAttribute(
                FinesRequestReference::DATE_FROM,
                $carbon->format(FinesRequestReference::DATE_FORMAT)
            )
        );

        return $this;
    }

    /**
     * Устанавливает конечную дату поиска.
     *
     * @param Carbon|\DateTime|int|string $date_time
     *
     * @return $this
     */
    public function dateTo($date_time)
    {
        $carbon = $this->convertToCarbon($date_time);
        $this->attributes->push(
            new MonetaAttribute(
                FinesRequestReference::DATE_TO,
                $carbon->format(FinesRequestReference::DATE_FORMAT)
            )
        );

        return $this;
    }

    /**
     * @todo Переписать
     *
     * @param array $response
     *
     * @return Fine[]|FineCollection
     */
    protected function prepare($response)
    {
        $result = new FineCollection;

        if (! isset($response['nextStep']) || $response['nextStep'] !== FineReference::STEP_PAY) {
            return $result;
        }
        foreach ($response['fields']['field'] as $field) {
            if (isset($field['attribute-name'])
                && $field['attribute-name'] === FineReference::FIELD_FINES
                && isset($field['enum'], $field['enum']['complexItem'])
                && is_array($field['enum']['complexItem'])
            ) {
                foreach ($field['enum']['complexItem'] as $complexItem) {
                    $fine = new Fine($complexItem);
                    $result->push($fine);
                }
                break;
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function createBody()
    {
        $attributes = [];
        foreach ($this->attributes as $attribute) {
            $attributes[] = $attribute->toAttribute('name');
        }

        return [
            'version'    => $this->version,
            'providerId' => $this->api->getConfigValue('fine_provider_id'),
            'fieldsInfo' => [
                'attribute' => $attributes,
            ],
        ];
    }
}
