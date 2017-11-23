<?php

namespace AvtoDev\MonetaApi\Types;

use AvtoDev\MonetaApi\References\ProviderReference;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

/**
 * Class Provider.
 *
 * Провайдер платежей
 *
 * @see ProviderReference
 */
class Provider extends AbstractType
{
    /**
     * Идентификатор провайдера.
     *
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $subProviderId;

    /**
     * Наименование провайдера.
     *
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $targetAccountId;

    /**
     * {@inheritdoc}
     */
    public function configure($content)
    {
        $arraySet = $this->convertToArray($content);
        foreach ((array) $arraySet as $key => $value) {
            switch (trim($key)) {
                case ProviderReference::FIELD_ID:
                    $this->id = $value;
                    break;
                case ProviderReference::FIELD_NAME:
                    $this->name = $value;
                    break;
                case ProviderReference::FIELD_SUB_PROVIDER_ID:
                    $this->subProviderId = $value;
                    break;
                case ProviderReference::FIELD_TARGET_ACCOUNT_ID:
                    $this->targetAccountId = $value;
                    break;
            }
            if (in_array($key, ProviderReference::getAll())) {
                $this->attributes->push(new MonetaAttribute($key, $value));
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSubProviderId()
    {
        return $this->subProviderId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTargetAccountId()
    {
        return $this->targetAccountId;
    }
}
