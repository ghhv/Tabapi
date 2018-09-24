<?php

namespace Mrgla55\Tabapi\Providers\Lumen;

use GuzzleHttp\Client;
use Mrgla55\Tabapi\Providers\BaseServiceProvider;
use Mrgla55\Tabapi\Providers\Lumen\LumenRedirect;
use Mrgla55\Tabapi\Providers\Laravel\LaravelCache;

class TabapiServiceProvider extends BaseServiceProvider
{
    /**
     * Returns the location of the package config file.
     *
     * @return string file location
     */
    protected function getConfigPath()
    {
        return __DIR__.'/../config/tabapi.php';
    }

    protected function getClient()
    {
        return new Client(['http_errors' => true]);
    }

    protected function getRedirect()
    {
        return new LumenRedirect(redirect());
    }

    protected function getStorage()
    {
        return new LumenCache(app('cache'), app('config'));
    }
}
