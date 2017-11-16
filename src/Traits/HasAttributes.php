<?php

namespace AvtoDev\MonetaApi\Traits;

use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

trait HasAttributes
{
    protected $attributesStack = [];

    /**
     * @param MonetaAttribute $attribute
     *
     * @return $this
     */
    public function pushAttribute(MonetaAttribute $attribute)
    {
        $this->attributesStack[$attribute->getName()] = $attribute;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearAttributes()
    {
        $this->attributesStack = [];

        return $this;
    }

    /**
     * @param MonetaAttribute $attribute
     *
     * @return bool
     */
    public function hasAttribute(MonetaAttribute $attribute)
    {
        return in_array($attribute, $this->attributes());
    }

    /**
     * @return MonetaAttribute[]
     */
    public function attributes()
    {
        return $this->attributesStack;
    }

    /**
     * @param $attributeType string
     *
     * @return bool
     */
    public function hasAttributeByType($attributeType)
    {
        return isset($this->attributes()[$attributeType]);
    }

    /**
     * @param $attributeType string
     *
     * @return bool
     */
    public function hasAttributeByValue($attributeType)
    {
        foreach ($this->attributes() as $attribute) {
            if ($attribute->getValue() === $attributeType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $attributeType
     *
     * @return MonetaAttribute|null
     */
    public function getAttributeByType($attributeType)
    {
        if (isset($this->attributes()[$attributeType])) {
            return $this->attributes()[$attributeType];
        }

        return null;
    }

    public function dropAttribute($attributeName)
    {
        if (isset($this->attributesStack[$attributeName])) {
            unset($this->attributesStack[$attributeName]);
        }

        return true;
    }
}
