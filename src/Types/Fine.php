<?php

namespace AvtoDev\MonetaApi\Types;

use Carbon\Carbon;
use AvtoDev\MonetaApi\References\FineReference;
use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\OperationInfoPaymentRequestReference;

/**
 * Class Fine.
 *
 * Штраф
 *
 * @see FineReference
 */
class Fine extends AbstractType
{
    /**
     * Уникальный идентификатор постановления.
     *
     * @var string
     */
    protected $id;

    /**
     * Сумма штрафа.
     *
     * @var float
     */
    protected $amount;

    /**
     * Название.
     *
     * @var string
     */
    protected $label;

    /**
     * Дата постановления.
     *
     * @var Carbon
     */
    protected $billDate;

    /**
     * Наименование поставщика.
     *
     * @var string
     */
    protected $soiName;

    /**
     * ИНН получателя.
     *
     * @var string
     */
    protected $wireUserInn;

    /**
     * КПП получателя.
     *
     * @var string
     */
    protected $wireKpp;

    /**
     * Номер счета получателя платежа.
     *
     * @var string
     */
    protected $wireBankAccount;

    /**
     * Наименование банка получателя платежа.
     *
     * @var string
     */
    protected $wireBankName;

    /**
     * БИК.
     *
     * @var string
     */
    protected $wireBankBik;

    /**
     * Назначение платежа.
     *
     * @var string
     */
    protected $wirePaymentPurpose;

    /**
     * Наименование получателя платежа.
     *
     * @var string
     */
    protected $wireUsername;

    /**
     * КБК.
     *
     * @var string
     */
    protected $wireKbk;

    /**
     * ОКТМО (ОКАТО).
     *
     * @var string
     */
    protected $wireOktmo;

    /**
     * Альтернативный идентификатор плательщика.
     *
     * @var string
     */
    protected $wireAltPayerIdentifier;

    /**
     * цифровая подпись параметров данного начисления (используется при оплате).
     *
     * @var string
     */
    protected $sign;

    /**
     * Сумма к оплате.
     *
     * @var int
     */
    protected $totalAmount;

    /**
     * @var bool
     */
    protected $isPaid;

    /**
     * размер скидки в %.
     *
     * @var int
     */
    protected $discountSize;

    /**
     * дата действия скидки по оплате.
     *
     * @var Carbon
     */
    protected $discountDate;

    /**
     * {@inheritdoc}
     */
    public function configure($content)
    {
        $arraySet = $this->convertToArray($content);
        foreach ((array) $arraySet as $key => $value) {
            switch (trim($key)) {
                case FineReference::FIELD_UIN:
                    $this->id = $value;
                    break;

                case FineReference::FIELD_AMOUNT:
                    $this->amount = $value;
                    break;

                case FineReference::FIELD_LABEL:
                    $this->label = $value;
                    break;

                case FineReference::FIELD_CONTENT:
                    $content = $this->convertToArray($value);
                    $config  = [];
                    foreach ($content as $item) {
                        $config[$item['name']] = (isset($item['value']))
                            ? $item['value']
                            : null;
                    }
                    $this->configure($config);
                    break;

                case FineReference::FIELD_BILL_DATE:
                    $this->billDate = $this->convertToCarbon($value, FineReference::DATE_FORMAT);
                    break;

                case FineReference::FIELD_SOI_NAME:
                    $this->soiName = $value;
                    break;

                case FineReference::FIELD_WIRE_USER_INN:
                    $this->wireUserInn = $value;
                    break;

                case FineReference::FIELD_WIRE_KPP:
                    $this->wireKpp = $value;
                    break;

                case FineReference::FIELD_WIRE_BANK_ACCOUNT:
                    $this->wireBankAccount = $value;
                    break;

                case FineReference::FIELD_WIRE_BANK_NAME:
                    $this->wireBankName = $value;
                    break;

                case FineReference::FIELD_WIRE_BANK_BIK:
                    $this->wireBankBik = $value;
                    break;

                case FineReference::FIELD_WIRE_PAYMENT_PURPOSE:
                    $this->wirePaymentPurpose = $value;
                    break;

                case FineReference::FIELD_WIRE_USERNAME:
                    $this->wireUsername = $value;
                    break;

                case FineReference::FIELD_WIRE_KBK:
                    $this->wireKbk = $value;
                    break;

                case FineReference::FIELD_WIRE_OKTMO:
                    $this->wireOktmo = $value;
                    break;

                case FineReference::FIELD_WIRE_ALT_PAYER_IDENTIFIER:
                    $this->wireAltPayerIdentifier = $value;
                    break;

                case FineReference::FIELD_SIGN:
                    $this->sign = $value;
                    break;

                case FineReference::FIELD_TOTAL_AMOUNT:
                    $this->totalAmount = $value;
                    break;

                case FineReference::FIELD_IS_PAID:
                    $this->isPaid = ($value === 'true' || $value === 1 || $value === true);
                    break;

                case FineReference::FIELD_DISCOUNT_SIZE:
                    $this->discountSize = $value;
                    break;

                case FineReference::FIELD_DISCOUNT_DATE:
                    $this->discountDate = $this->convertToCarbon($value, FineReference::DATE_FORMAT);
                    break;
            }
            if ($key !== FineReference::FIELD_CONTENT) {
                $this->attributes->push(new MonetaAttribute($key, $value));
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return Carbon
     */
    public function getBillDate()
    {
        return $this->billDate;
    }

    public function getSoiName()
    {
        return $this->soiName;
    }

    public function getWireUserInn()
    {
        return $this->wireUserInn;
    }

    public function getWireKpp()
    {
        return $this->wireKpp;
    }

    public function getWireBankAccount()
    {
        return $this->wireBankAccount;
    }

    public function getWireBankName()
    {
        return $this->wireBankName;
    }

    public function getWireBankBik()
    {
        return $this->wireBankBik;
    }

    public function getWirePaymentPurpose()
    {
        return $this->wirePaymentPurpose;
    }

    public function getWireUsername()
    {
        return $this->wireUsername;
    }

    public function getWireKbk()
    {
        return $this->wireKbk;
    }

    public function getWireOktmo()
    {
        return $this->wireOktmo;
    }

    public function getWireAltPayerIdentifier()
    {
        return $this->wireAltPayerIdentifier;
    }

    public function getSign()
    {
        return $this->sign;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @return bool
     */
    public function getIsPaid()
    {
        return $this->isPaid;
    }

    public function getDiscountSize()
    {
        return $this->discountSize;
    }

    /**
     * @return Carbon
     */
    public function getDiscountDate()
    {
        return $this->discountDate;
    }

    /**
     * Получить атрибуты для передачи в оплату.
     *
     * @return AttributeCollection|MonetaAttribute[]
     */
    public function getOperationInfo()
    {
        $attributes = new AttributeCollection;
        foreach (OperationInfoPaymentRequestReference::getAll() as $type) {
            $attribute = $this->attributes->getByType($type);
            if ($attribute) {
                $attributes->push($attribute);
            }
        }

        return $attributes;
    }
}
