<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests\Mock;

use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Types\Requests\AbstractRequest;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

class RequestMock extends AbstractRequest
{
    /**
     * @var AttributeCollection
     */
    public $attributes;

    protected $methodName = 'MethodTest';

    protected $required   = [
        'test',
    ];

    public function prepare($response)
    {
        return $response;
    }

    public function setTest($value)
    {
        $this->attributes->push(new MonetaAttribute('test', $value));

        return $this;
    }

    protected function createBody()
    {
        $attributes = [];
        foreach ($this->attributes as $attribute) {
            $attributes[] = $attribute->toAttribute('key');
        }

        return [
            'someParams' => $attributes,
        ];
    }
}
