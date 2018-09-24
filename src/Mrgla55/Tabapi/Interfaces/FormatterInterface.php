<?php

namespace Mrgla55\Tabapi\Interfaces;

interface FormatterInterface
{
    public function setHeaders();
    public function setBody($data);
    public function formatResponse($response);
}