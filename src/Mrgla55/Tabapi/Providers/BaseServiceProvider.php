<?php

namespace Mrgla55\Tabapi\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Mrgla55\Tabapi\Client as TabapiClient;
use Mrgla55\Tabapi\Providers\Laravel\LaravelCache;
use Mrgla55\Tabapi\Providers\Laravel\LaravelEvent;
use Mrgla55\Tabapi\Providers\Laravel\LaravelEncryptor;
use Mrgla55\Tabapi\Providers\Laravel\LaravelInput;
use Mrgla55\Tabapi\Providers\Laravel\LaravelRedirect;
use Mrgla55\Tabapi\Providers\Laravel\LaravelSession;

use Mrgla55\Tabapi\Formatters\JSONFormatter;
use Mrgla55\Tabapi\Formatters\URLEncodedFormatter;
use Mrgla55\Tabapi\Formatters\XMLFormatter;

use Mrgla55\Tabapi\Repositories\InstanceURLRepository;
use Mrgla55\Tabapi\Repositories\RefreshTokenRepository;
use Mrgla55\Tabapi\Repositories\ResourceRepository;
use Mrgla55\Tabapi\Repositories\StateRepository;
use Mrgla55\Tabapi\Repositories\TokenRepository;
use Mrgla55\Tabapi\Repositories\VersionRepository;


abstract class BaseServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Returns the location of the package config file.
     *
     * @return string file location
     */
    abstract protected function getConfigPath();

    /**
     * Returns client implementation
     *
     * @return GuzzleHttp\Client
     */
    protected abstract function getClient();

    /**
     * Returns client implementation
     *
     * @return GuzzleHttp\Client
     */
    protected abstract function getRedirect();

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if (!method_exists($this, 'getConfigPath')) return;

        $this->publishes([
            __DIR__.'/../../../config/config.php' => $this->getConfigPath(),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('tabapi', function ($app) {

            // Config options
            $settings           = config('tabapi');
            $storageType        = config('tabapi.storage.type');

            // Dependencies
            $httpClient    = $this->getClient();
            $input     = new LaravelInput(app('request'));
            $event     = new LaravelEvent(app('events'));
            $encryptor = new LaravelEncryptor(app('encrypter'));
            $redirect  = $this->getRedirect();
            $storage   = $this->getStorage($storageType);

            $refreshTokenRepo = new RefreshTokenRepository($encryptor, $storage);
            $tokenRepo        = new TokenRepository($encryptor, $storage);
            $resourceRepo     = new ResourceRepository($storage);
            $versionRepo      = new VersionRepository($storage);
            $instanceURLRepo  = new InstanceURLRepository($tokenRepo, $settings);
            $stateRepo        = new StateRepository($storage);

            $formatter = new JSONFormatter($tokenRepo, $settings);

			$tabapi = new TabapiClient(
				$httpClient,
				$encryptor,
				$event,
				$input,
				$redirect,
				$instanceURLRepo,
				$refreshTokenRepo,
				$resourceRepo,
				$stateRepo,
				$tokenRepo,
				$versionRepo,
				$formatter,
				$settings);			
			
            return $tabapi;
        });
    }
	/**
     * @param  String $tokenURL
     * @param  Array $parameters
     * @return String
     */
    private function getAuthToken($url)
    {
        $parameters['form_params'] = [
            'grant_type'    => 'password',
            'client_id'     => $this->credentials['consumerKey'],
            'client_secret' => $this->credentials['consumerSecret'],
            'username'      => $this->credentials['username'],
            'password'      => $this->credentials['password'],
        ];
        // \Psr\Http\Message\ResponseInterface
        $response = $this->httpClient->request('post', $url, $parameters);
        $authTokenDecoded = json_decode($response->getBody(), true);
        $this->handleAuthenticationErrors($authTokenDecoded);
        return $authTokenDecoded;
    }
}
