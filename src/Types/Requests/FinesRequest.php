<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\References\FinesRequestReference;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

class FinesRequest extends AbstractRequest
{
    protected $version          = 'VERSION_3';

    protected $methodName       = 'GetNextStepRequest';

    protected $searchAttributes = [];

    protected $required         = [
        FinesRequestReference::SEARCH_METHOD,
    ];

    public function bySTS($sts)
    {
        $this->attributes->push(new MonetaAttribute(FinesRequestReference::SEARCH_METHOD,
            FinesRequestReference::SEARCH_METHOD_PERSONAL));
        $this->attributes->push(new MonetaAttribute(FinesRequestReference::SEARCH_BY_STS, $sts));

        return $this;
    }

    public function byUin($uin)
    {
        $this->attributes->push(new MonetaAttribute(FinesRequestReference::SEARCH_METHOD,
            FinesRequestReference::SEARCH_METHOD_UIN));
        $this->attributes->push(new MonetaAttribute(FinesRequestReference::SEARCH_BY_UIN, $uin));

        return $this;
    }

    public function byDriverLicense($driverLicense)
    {
        $this->attributes->push(new MonetaAttribute(FinesRequestReference::SEARCH_METHOD,
            FinesRequestReference::SEARCH_METHOD_PERSONAL));
        $this->attributes->push(new MonetaAttribute(FinesRequestReference::SEARCH_BY_DRIVE_LICENCE, $driverLicense));

        return $this;
    }

    public function includeNonPaid()
    {
        $this->attributes->push(new MonetaAttribute(FinesRequestReference::CHARGE_STATUS,
            FinesRequestReference::CHARGE_STATUS_BOTH));

        return $this;
    }

    /**
     * @param $response
     *
     * @return Fine[]
     */
    public function prepare($response)
    {
        $return = [];

        if (! isset($response->GetNextStepResponse->nextStep) || $response->GetNextStepResponse->nextStep != 'PAY') {
            return $return;
        }
        foreach ($response->GetNextStepResponse->fields->field as $field) {
            if (
                isset($field->{'attribute-name'})
                && $field->{'attribute-name'} == 'CUSTOMFIELD:105'
                && isset($field->enum) && is_array($field->enum->complexItem)
            ) {
                foreach ($field->enum->complexItem as $complexItem) {
                    $fine     = new Fine($complexItem);
                    $return[] = $fine;
                }
            }
        }

        return $return;
    }

    public function dateFrom($date_time)
    {
        $carbon = $this->convertToCarbon($date_time);
        $this->attributes->push(
            new MonetaAttribute(
                FinesRequestReference::DATE_FROM, $carbon->format(FinesRequestReference::DATE_FORMAT)
            )
        );

        return $this;
    }

    public function dateTo($date_time)
    {
        $carbon = $this->convertToCarbon($date_time);
        $this->attributes->push(
            new MonetaAttribute(
                FinesRequestReference::DATE_TO, $carbon->format(FinesRequestReference::DATE_FORMAT)
            )
        );

        return $this;
    }

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
