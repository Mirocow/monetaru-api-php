<?php

namespace AvtoDev\MonetaApi\Traits;

use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;

trait CheckRequired
{
    /**
     * Проверка обязательных к запонению аттрибутов.
     *
     * @throws MonetaBadRequestException
     */
    protected function checkRequired()
    {
        foreach ($this->getRequiredReference() as $attribute) {
            if (! $this->getCollection()->hasByType($attribute)) {
                throw new MonetaBadRequestException("Не заполнен обязательный атрибут: $attribute", '500.1');
            }
        }
    }

    /**
     * @return AttributeCollection
     */
    abstract protected function getCollection();

    /**
     * @return array
     */
    abstract protected function getRequiredReference();
}
