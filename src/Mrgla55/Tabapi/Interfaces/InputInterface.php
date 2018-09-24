<?php

namespace Mrgla55\Tabapi\Interfaces;

interface InputInterface
{
    /**
     * Get input from response.
     *
     * @param string $parameter
     *
     * @return mixed
     */
    public function get($parameter);
}
