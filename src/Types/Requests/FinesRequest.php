<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\References\CommonReference;
use AvtoDev\MonetaApi\References\FinesRequestReference;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

class FinesRequest extends AbstractRequest
{
    protected $version          = 'VERSION_3';

    protected $methodName       = 'GetNextStepRequest';

    protected $searchAttributes = [];

    public function bySTS($sts)
    {
        $this->pushAttribute(new MonetaAttribute(FinesRequestReference::SEARCH_METHOD,
            FinesRequestReference::SEARCH_METHOD_PERSONAL));
        $this->pushAttribute(new MonetaAttribute(FinesRequestReference::SEARCH_BY_STS, $sts));

        return $this;
    }

    public function byUin($uin)
    {
        $this->pushAttribute(new MonetaAttribute(FinesRequestReference::SEARCH_METHOD,
            FinesRequestReference::SEARCH_METHOD_UIN));
        $this->pushAttribute(new MonetaAttribute(FinesRequestReference::SEARCH_BY_UIN, $uin));

        return $this;
    }

    public function byDriverLicense($driverLicense)
    {
        $this->pushAttribute(new MonetaAttribute(FinesRequestReference::SEARCH_METHOD,
            FinesRequestReference::SEARCH_METHOD_PERSONAL));
        $this->pushAttribute(new MonetaAttribute(FinesRequestReference::SEARCH_BY_DRIVE_LICENCE, $driverLicense));

        return $this;
    }

    public function includeNonPaid()
    {
        $this->pushAttribute(new MonetaAttribute(FinesRequestReference::CHARGE_STATUS,
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

        if ($response->GetNextStepResponse->nextStep != 'PAY') {
            return $return;
        }
        foreach ($response->GetNextStepResponse->fields->field as $field) {
            if (
                isset($field->{'attribute-name'})
                && $field->{'attribute-name'} == 'CUSTOMFIELD:105'
                && isset($field->enum) && is_array($field->enum->complexItem)
            ) {
                foreach ($field->enum->complexItem as $complexItem) {
                    $fine = new Fine($complexItem);
                    $fine->configure([CommonReference::PROVIDER_ID, $this->providerId]);
                    $return[] = $fine;
                }
            }
        }

        return $return;
    }

    public function dateFrom(\DateTime $date_time)
    {
        $this->searchAttributes[] =
            ['name' => 'CUSTOMFIELD:112', 'value' => $date_time->format('d-m-Y')];
    }

    public function dateTo(\DateTime $date_time)
    {
        $this->searchAttributes[] =
            ['name' => 'CUSTOMFIELD:113', 'value' => $date_time->format('d-m-Y')];
    }

    protected function createBody()
    {
        $attributes = [];
        foreach ($this->attributes() as $attribute) {
            $attributes[] = $attribute->toAttribute('name');
        }

        return [
            'version'    => $this->version,
            'providerId' => $this->providerId,
            'fieldsInfo' => [
                'attribute' => $attributes,
            ],
        ];
    }
}
