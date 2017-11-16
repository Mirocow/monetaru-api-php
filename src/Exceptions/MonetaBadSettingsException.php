<?php

namespace AvtoDev\MonetaApi\Exceptions;

use Throwable;

class MonetaBadSettingsException extends AbstractMonetaException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
