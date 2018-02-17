<?php

namespace AvtoDev\MonetaApi\Support;

use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

/**
 * Class AttributeCollection.
 *
 * Коллекция атрибутов
 */
class AttributeCollection extends AbstractCollection
{
    /**
     * @var MonetaAttribute[]
     */
    protected $stack = [];

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->stack);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->stack);
    }

    /**
     * {@inheritdoc}
     *
     * @return MonetaAttribute
     */
    public function current()
    {
        return current($this->stack);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->stack);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        next($this->stack);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return key($this->stack) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function push(MonetaAttribute $attribute)
    {
        $this->stack[$attribute->getName()] = $attribute;
    }

    /**
     * Проверяет наличие атрибута в коллекции.
     *
     * @param MonetaAttribute $attribute
     *
     * @return bool
     */
    public function has(MonetaAttribute $attribute)
    {
        return in_array($attribute, $this->stack);
    }

    /**
     * Проверяет наличие атрибута в коллекции.
     *
     * @param $attributeType string
     *
     * @return bool
     */
    public function hasByType($attributeType)
    {
        return isset($this->stack[$attributeType]);
    }

    /**
     * Проверяет наличие атрибута в коллекции.
     *
     * @param $attributeType string
     *
     * @return bool
     */
    public function hasByValue($attributeType)
    {
        $stack = $this->stack;
        reset($stack);
        foreach ($stack as $attribute) {
            if ($attribute->getValue() === $attributeType) {
                return true;
            }
        }

        return false;
    }

    /**
     * Получает атрибут по имени.
     *
     * @param $attributeType
     *
     * @return MonetaAttribute|null
     */
    public function getByType($attributeType)
    {
        if (isset($this->stack[$attributeType])) {
            return $this->stack[$attributeType];
        }
    }

    /**
     * Удаляет атрибут из коллекции.
     *
     * @param $attributeName
     */
    public function drop($attributeName)
    {
        if (isset($this->stack[$attributeName])) {
            unset($this->stack[$attributeName]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $return = [];
        foreach ($this->stack as $attribute) {
            $return = array_merge($return, $attribute->toArray());
        }

        return $return;
    }
}
