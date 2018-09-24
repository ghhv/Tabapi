<?php

namespace Mrgla55\Tabapi\Providers\Laravel;

use GuzzleHttp\Client;
use Mrgla55\Tabapi\Providers\BaseServiceProvider;
use Mrgla55\Tabapi\Providers\Laravel\LaravelCache;
use Mrgla55\Tabapi\Providers\Laravel\LaravelSession;

class TabapiServiceProvider extends BaseServiceProvider
{
    /**
     * Returns the location of the package config file.
     *
     * @return string file location
     */
    protected function getConfigPath()
    {
        return config_path('tabapi.php');
    }

    protected function getClient()
    {
        return new Client(['http_errors' => true]);
    }

    protected function getRedirect()
    {
        return new LaravelRedirect(app('redirect'));
    }

    protected function getStorage($storageType)
    {
        switch ($storageType) {
            case 'session':
                $storage = new LaravelSession(app('config'), app('request')->session());
                break;
            case 'cache':
                $storage = new LaravelCache(app('config'), app('cache')->store());
                break;
            default:
                $storage = new LaravelSession(app('config'), app('request')->session());
        }

        return $storage;
    }
}
