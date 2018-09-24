# Tabapi
TAB Studio REST API Client for Laravel 5

[![Laravel](https://img.shields.io/badge/Laravel-5.5-orange.svg?style=flat-square)](http://laravel.com)
[![Latest Stable Version](https://img.shields.io/packagist/v/mrgla55/Tabapi.svg?style=flat-square)](https://packagist.org/packages/mrgla55/Tabapi)
[![Total Downloads](https://img.shields.io/packagist/dt/mrgla55/Tabapi.svg?style=flat-square)](https://packagist.org/packages/mrgla55/Tabapi)
[![License](https://img.shields.io/packagist/l/mrgla55/Tabapi.svg?style=flat-square)](https://packagist.org/packages/mrgla55/Tabapi)
[![Build Status](https://img.shields.io/travis/mrgla55/Tabapi.svg?style=flat-square)](https://travis-ci.org/mrgla55/Tabapi)

TAB REST API client for Laravel.

While this package is built for Laravel, it has been decoupled so that it can be extended into any framework or vanilla PHP application. Currently the only support is for Laravel 4, 5 and Lumen.

## Installation

Tabapi can be installed through composer. Open your `composer.json` file and add the following to the `require` key:
```php
"mrgla55/tabapi": "0.*"
```
Next run `composer update` from the command line to install the package.

### Laravel Installation

Add the service provider and alias to your `config/app.php` file:

```php
mrgla55\Tabapi\Providers\Laravel\TabapiServiceProvider::class
'Tabapi' => mrgla55\Tabapi\Providers\Laravel\Facades\Tabapi::class
```

>For Laravel 4, add `mrgla55\Tabapi\Providers\Laravel4\TabapiServiceProvider` in `app/config/app.php`. Alias will remain the same.

### Lumen Installation 

```php
class_alias('mrgla55\Tabapi\Providers\Laravel\Facades\Tabapi', 'Tabapi');
$app->register(mrgla55\Tabapi\Providers\Lumen\TabapiServiceProvider::class);
$app->configure('Tabapi');
$app->withFacades();
```
Then you'll utilize the Lumen service provider by registering it in the `bootstrap/app.php` file.

### Configuration
You will need a configuration file to add your credentials. Publish a config file using the `artisan` command:
```bash
php artisan vendor:publish
```
You can find the config file in: `config/tabapi.php`

>For Lumen, you should copy the config file from `src/config/config.php` and add it to a `Tabapi.php` configuration file under a config directory in the root of your application. 

>For Laravel 4, run `php artisan config:publish mrgla55/Tabapi`. It will be found in `app/config/mrgla55/Tabapi/config.php`

## Getting Started
### Setting up a TAB Studio Account
1. Go to https://studio.tab.com.au/
2. You will need to have a valid TAB account number
3. Click register and complete the form. Registration approval normally takes 2-3 days.
4. Log in to TAB Studio
5. Click "Settings" from the top right menu
6. Note your Client Id and Client Secret and put them into your .env file as TAB_CLIENT_ID and TAB_CLIENT_SECRET, or update your tabapi config file.


### Setup
Creating routes

##### Test API flow
```php
Route::get('/versions', function()
{
    return Tabapi::versions();
});
```

## API Requests

All resources are requested dynamically using method overloading.

First, determine which resources you have access to by calling:
```php
Tabapi::resources();
```
Result:
```php
Array
(
    [account:authenticate] => https://webapi.tab.com.au/v1/account-service/tab/authenticate
    [account:balance] => https://webapi.tab.com.au/v1/account-service/tab/accounts/{accountNumber}/balance
	...
)
```
Next, call resources by referring to the specified key. Replace the colon ':' with and underscore '_'.
Embedded parameters in the base url are replaced with arguments. Any left over arguments are added to the query string.

For instance:
```php
Tabapi::lookup_countries_allowed();
```
or
```php
Tabapi::info_sports(['jurisdiction' => 'nsw']);;
```


For more information about Guzzle responses and event listeners, refer to their [documentation](http://guzzle.readthedocs.org).