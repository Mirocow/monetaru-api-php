<?php

namespace AvtoDev\MonetaApi\Types;

use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\OperationInfoReference;

class OperationDetails extends AbstractType
{
    /**
     * Идентификатор платежа в системе МОНЕТА.РУ.
     *
     * @var int
     */
    protected $id;

    /**
     * Сумма по операции.
     *
     * @var int
     */
    protected $sourceAmount;

    /**
     * Статус операции.
     *
     * @var string
     */
    protected $statusId;

    /**
     * @var
     */
    protected $sourceAmountTotal;

    public function configure($content)
    {
        $config = $this->convertToArray($content);
        foreach ($config as $key => $value) {
            switch ($key) {
                case 'id':
                    $this->id = (int) $value;
                    break;
                case 'operation':
                    $this->configure($value);
                    break;
                case 'attribute':
                    $configure = [];
                    foreach ($value as $item) {
                        $item                    = (array) $item;
                        $configure[$item['key']] = (isset($item['value']))
                            ? $item['value']
                            : null;
                    }
                    $this->configure($configure);
                    break;
                case OperationInfoReference::FIELD_SOURCE_AMOUNT:
                    $this->sourceAmount = (int) $value;
                    break;
                case OperationInfoReference::FIELD_SOURCE_AMOUNT_TOTAL:
                    $this->sourceAmountTotal = (int) $value;
                    break;
                case OperationInfoReference::FIELD_STATUS:
                    $this->statusId = trim($value);
                    break;
            }
            if ($key !== 'operation' && $key !== 'attribute') {
                $this->attributes->push(new MonetaAttribute($key, $value));
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

    /**
     * @return int
     */
    public function getSourceAmount()
    {
        return $this->sourceAmount;
    }

    /**
     * @return mixed
     */
    public function getSourceAmountTotal()
    {
        return $this->sourceAmountTotal;
    }

    /**
     * @return string
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    public function isSuccessful()
    {
        return $this->getStatusId() === 'SUCCEED';
    }
}
