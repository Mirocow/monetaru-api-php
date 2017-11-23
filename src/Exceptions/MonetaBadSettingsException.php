<?php

namespace AvtoDev\MonetaApi\Exceptions;

use Throwable;

/**
 * Class MonetaBadSettingsException.
 *
 * Ошибка настроек клиента
 */
class MonetaBadSettingsException extends AbstractMonetaException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
