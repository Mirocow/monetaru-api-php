<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests\Mock;

use AvtoDev\MonetaApi\Types\Requests\AbstractRequest;

class RequestMock extends AbstractRequest
{
    protected $methodName = 'MethodTest';

    public function prepare($response)
    {
        return $response;
    }

    protected function createBody()
    {
        $attributes = [];
        foreach ($this->attributes() as $attribute) {
            $attributes[] = $attribute->toAttribute('key');
        }

        return [
            'someParams' => $attributes,
        ];
    }
}
