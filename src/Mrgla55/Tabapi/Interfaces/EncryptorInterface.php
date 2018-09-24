<?php

namespace Mrgla55\Tabapi\Interfaces;

interface EncryptorInterface
{
    /**
     * Encrypt
     *
     * @return mixed
     */
    public function encrypt($token);

    /**
     * Decrypt
     *
     * @return mixed
     */
    public function decrypt($token);
}
