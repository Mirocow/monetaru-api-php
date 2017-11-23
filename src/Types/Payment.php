<?php

namespace AvtoDev\MonetaApi\Types;

use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

class Payment extends AbstractType
{
    /**
     * Идентификатор транзакции.
     *
     * @var int
     */
    protected $id;

    /**
     * {@inheritdoc}
     */
    public function configure($content)
    {
        $config = $this->convertToArray($content);
        foreach ((array) $config as $key => $value) {
            switch ($key) {
                case 'id':
                    $this->id = $value;
                    break;
                case 'attribute':
                    $attributes = $this->convertToArray($value);
                    foreach ((array) $attributes as $attribute) {
                        $this->attributes->push(new MonetaAttribute($attribute->key, $attribute->value));
                    }
                    break;
            }
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
