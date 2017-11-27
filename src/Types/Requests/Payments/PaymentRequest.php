<?php

namespace AvtoDev\MonetaApi\Types\Requests\Payments;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Types\Payment;
use AvtoDev\MonetaApi\Clients\MonetaApi;
use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\PaymentRequestReference;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;
use AvtoDev\MonetaApi\References\OperationInfoPaymentRequestReference;

/**
 * Class PaymentRequest.
 *
 * Запрос на перевод средств. Может использовать Payment Token.
 *
 * @see PaymentRequestReference
 * @see OperationInfoPaymentRequestReference
 * @todo: При указании данных карты Access is denied
 */
class PaymentRequest extends AbstractPaymentRequest
{
    /**
     * {@inheritdoc}
     */
    protected $methodName = 'PaymentRequest';

    /**
     * {@inheritdoc}
     */
    protected $responseName = 'PaymentResponse';

    /**
     * @var AttributeCollection
     */
    protected $operationInfo;

    /**
     * {@inheritdoc}
     */
    protected $required = [
        PaymentRequestReference::FIELD_PAYER,
        PaymentRequestReference::FIELD_PAYEE,
        PaymentRequestReference::FIELD_AMOUNT,
        PaymentRequestReference::FIELD_IS_PAYER_AMOUNT,
    ];

    /**
     * Поля обязательные к заполнению.
     *
     * @var array
     */
    protected $operationInfoRequired = [
        OperationInfoPaymentRequestReference::PAYER_PHONE,
        OperationInfoPaymentRequestReference::PAYER_FIO,
    ];

    /**
     * PaymentRequest constructor.
     *
     * @param MonetaApi $api
     */
    public function __construct(MonetaApi $api)
    {
        parent::__construct($api);
        $this->operationInfo = new AttributeCollection;
    }

    /**
     * {@inheritdoc}
     *
     * @return Payment
     */
    public function exec()
    {
        return parent::exec();
    }

    /**
     * {@inheritdoc}
     *
     * @return Payment
     */
    public function prepare($response)
    {
        return new Payment($response);
    }

    /**
     * Устанавливает номер счета плательщика.
     * Обязателен.
     *
     * @param string $accountNumber
     *
     * @return $this
     */
    public function setAccountNumber($accountNumber)
    {
        $this->attributes->push(new MonetaAttribute(
            PaymentRequestReference::FIELD_PAYER,
            (string) trim($accountNumber)
        ));

        return $this;
    }

    /**
     * Устанавливает платежный пароль счета.
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPaymentPassword($password)
    {
        if (! empty($password)) {
            $this->attributes->push(new MonetaAttribute(
                PaymentRequestReference::FIELD_PAYMENT_PASSWORD,
                (string) trim($password)
            ));
        }

        return $this;
    }

    /**
     * Устанавливает валюту списания.
     * true  - В валюте плательщика.
     * false - В валюте получателя.
     * Обязателен.
     *
     * @param bool $isPayerAmount
     *
     * @return $this
     */
    public function setIsPayerAmount($isPayerAmount = true)
    {
        $this->attributes->push(new MonetaAttribute(PaymentRequestReference::FIELD_IS_PAYER_AMOUNT, (bool)
        $isPayerAmount));

        return $this;
    }

    /**
     * Устанавливает счет назначения.
     * Обязателен.
     *
     * @param $accountNumber
     *
     * @return $this
     */
    public function setDestinationAccount($accountNumber)
    {
        $this->attributes->push(new MonetaAttribute(
            PaymentRequestReference::FIELD_PAYEE,
            (string) trim($accountNumber)
        ));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPayerFio($fio)
    {
        parent::setPayerFio($fio);
        $this->operationInfo->push(new MonetaAttribute(OperationInfoPaymentRequestReference::PAYER_FIO, $this->fio));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPayerPhone($phone)
    {
        parent::setPayerPhone($phone);
        $this->operationInfo->push(
            new MonetaAttribute(OperationInfoPaymentRequestReference::PAYER_PHONE, $this->phone)
        );

        return $this;
    }

    /**
     * Заполняет запрос по штрафу.
     *
     * @param Fine $fine
     *
     * @return $this
     */
    public function setFine(Fine $fine)
    {
        $this->setAmount($fine->getAmount());

        foreach ($fine->getOperationInfo() as $attribute) {
            $this->operationInfo->push($attribute);
        }

        $this->operationInfo->push(
            new MonetaAttribute(OperationInfoPaymentRequestReference::FIELD_UIN, (string) $fine->getId())
        );

        $this->operationInfo->push(new MonetaAttribute(
            OperationInfoPaymentRequestReference::SUB_PROVIDER_ID,
            (string) $this->api->getConfigValue('accounts.provider.sub_id')
        ));

        return $this;
    }

    /**
     * Устанавливает сумму перевода.
     * Обязательно.
     *
     * @param $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->attributes->push(new MonetaAttribute(PaymentRequestReference::FIELD_AMOUNT, (float) $amount));

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function setParentId($id)
    {
        $this->operationInfo->push(new MonetaAttribute(PaymentRequestReference::FIELD_PARENT_ID, (int) $id));

        return $this;
    }

    /**
     * @param $commission
     *
     * @return $this
     */
    public function setCommission($commission)
    {
        $this->operationInfo->push(new MonetaAttribute(PaymentRequestReference::FIELD_COMMISSION, (float) $commission));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function checkRequired()
    {
        parent::checkRequired();
        if (! $this->operationInfo->isEmpty()) {
            foreach ($this->operationInfoRequired as $attribute) {
                if (! $this->operationInfo->hasByType($attribute)) {
                    throw new MonetaBadRequestException("Не заполнен обязательный атрибут: $attribute", '500.1');
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createBody()
    {
        $attributes = [];
        foreach ($this->attributes as $attribute) {
            $attributes[$attribute->getName()] = $attribute->getValue();
        }

        $operationInfo = [];
        foreach ($this->operationInfo as $attribute) {
            $operationInfo[] = $attribute->toAttribute('key');
        }

        return array_merge(
            [
                'version'                                     => $this->version,
                PaymentRequestReference::FIELD_OPERATION_INFO => [
                    'attribute' => $operationInfo,
                ],
            ],
            $attributes
        );
    }
}
