<?php

namespace AvtoDev\MonetaApi\Types;

use Carbon\Carbon;
use AvtoDev\MonetaApi\References\FineReference;
use AvtoDev\MonetaApi\References\CommonReference;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\OperationInfoPaymentRequestReference;

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
     * Id получателя в системе МОНЕТА.
     *
     * @var string
     */
    protected $providerId;

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
                    foreach ($value as $item) {
                        $this->configure(
                            new MonetaAttribute(
                                $item->name,
                                (isset($item->value))
                                    ? $item->value
                                    : null));
                    }
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
                    $this->isPaid = (bool) $value;
                    break;

                case FineReference::FIELD_DISCOUNT_SIZE:
                    $this->discountSize = $value;
                    break;

                case FineReference::FIELD_DISCOUNT_DATE:
                    $this->discountDate = $this->convertToCarbon($value, FineReference::DATE_FORMAT);
                    break;
                case CommonReference::PROVIDER_ID:
                    $this->providerId = $value;
                    break;
            }
            if ($key !== FineReference::FIELD_CONTENT && in_array($key, FineReference::getAll())) {
                $this->pushAttribute(new MonetaAttribute($key, $value));
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

    public function getIsPaid()
    {
        return $this->isPaid;
    }

    public function getDiscountSize()
    {
        return $this->discountSize;
    }

    public function getDiscountDate()
    {
        return $this->discountDate;
    }

    public function getProviderId()
    {
        return $this->providerId;
    }

    public function getOperationInfo()
    {
        $attributes = [];
        foreach (OperationInfoPaymentRequestReference::getAll() as $type) {
            $attribute = $this->getAttributeByType($type);
            if ($attribute) {
                $attributes[] = $attribute;
            }
        }

        return $attributes;
    }
}
