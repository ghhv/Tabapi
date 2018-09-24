<?php

namespace Mrgla55\Tabapi\Exceptions;

use GuzzleHttp\Exception\RequestException;

class TabException extends RequestException
{
    public function __construct($message, RequestException $e)
    {
        parent::__construct($message, $e->getRequest(), $e->getResponse(), $e);
    }
}
