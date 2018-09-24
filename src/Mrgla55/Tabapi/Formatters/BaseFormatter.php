<?php

namespace Mrgla55\Tabapi\Formatters;

use Mrgla55\Tabapi\Interfaces\FormatterInterface;

class BaseFormatter implements FormatterInterface
{
    public function setHeaders()
    {
        $headers['Accept'] = 'application/json';
        $headers['Content-Type'] = 'application/json';

        return $headers;
    }

    public function setBody($data)
    {
        return json_encode($data);
    }

    public function formatResponse($response)
    {
        print_r($response>getBody());
        return json_decode($response->getBody(), true);
    }
}