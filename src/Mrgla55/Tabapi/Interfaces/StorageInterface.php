<?php

namespace Mrgla55\Tabapi\Interfaces;

interface StorageInterface
{
    /**
     * Store into storage.
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function put($key, $value);

    /**
     * Get from storage.
     *
     * @param $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Check if storage has a key stored.
     *
     * @param $key
     *
     * @return bool
     */
    public function has($key);
}
