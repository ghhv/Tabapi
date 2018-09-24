# Tabapi
TAB REST API Client for Laravel 5

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
You can find the config file in: `config/Tabapi.php`

>For Lumen, you should copy the config file from `src/config/config.php` and add it to a `Tabapi.php` configuration file under a config directory in the root of your application. 

>For Laravel 4, run `php artisan config:publish mrgla55/Tabapi`. It will be found in `app/config/mrgla55/Tabapi/config.php`

## Getting Started
### Setting up a Connected App
1. Log into to your Salesforce org
2. Click on Setup in the upper right-hand menu
3. Under Build click `Create > Apps`
4. Scroll to the bottom and click `New` under Connected Apps.
5. Enter the following details for the remote application:
    * Connected App Name
    * API Name
    * Contact Email
    * Enable OAuth Settings under the API dropdown
    * Callback URL
    * Select access scope (If you need a refresh token, specify it here)
6. Click `Save`

After saving, you will now be given a Consumer Key and Consumer Secret. Update your config file with values for `consumerKey`, `consumerSecret`, `loginURL` and `callbackURI`.

### Setup
Creating authentication routes

##### Web Server authentication flow
```php
Route::get('/authenticate', function()
{
    return Tabapi::authenticate();
});

Route::get('/callback', function()
{
    Tabapi::callback();

    return Redirect::to('/');
});
```
##### Username-Password authentication flow
With the Username Password flow, you can directly authenticate with the `Tabapi::authenticate()` method.

>To use this authentication you must add your username, and password to the config file. Security token might need to be ammended to your password unless your IP address is whitelisted.

```php
Route::get('/authenticate', function()
{
    Tabapi::authenticate();
    return Redirect::to('/');
});
```

#### Custom login urls
Sometimes users will need to connect to a sandbox or custom url. To do this, simply pass the url as an argument for the authenticatation method:
```php
Route::get('/authenticate', function()
{
    $loginURL = 'https://test.salesforce.com';

    return Tabapi::authenticate($loginURL);
});
```
>Note: You can specify a default login URL in your config file.

## Basic usage
After authentication, your app will store an encrypted authentication token which can be used to make API requests.
### Query a record
```php
Tabapi::query('SELECT Id FROM Account');
```
Result:
```JavaScript
{
    "totalSize": 2,
    "done": true,
    "records": [
        {
            "attributes": {
                "type": "Account",
                "url": "\/services\/data\/v30.0\/sobjects\/Account\/001i000000xxx"
            },
            "Id": "001i000000xxx"
        },
        {
            "attributes": {
                "type": "Account",
                "url": "\/services\/data\/v30.0\/sobjects\/Account\/001i000000xxx"
            },
            "Id": "001i000000xxx"
        }
    ]
}
```
If you are querying more than 2000 records, you response will include:
```
"nextRecordsUrl" : "/services/data/v20.0/query/01gD0000002HU6KIAW-2000"
```

Simply, call `Tabapi::next($nextRecordsUrl)` to return the next 2000 records.

### Create a new record
Records can be created using the following format.
```php
$body = ['Name' => 'New Account'];
Tabapi::sobjects('Account',[
    'method' => 'post',
    'body'   => $body]);
```

### Update a record
Update a record with the PUT method.

```php
$body = [
    'Name'  => 'Acme'
    'Phone' => '555-555-5555'];

Tabapi::sobjects('Account/001i000000xxx',[
    'method' => 'put',
    'body'   => $body]);
```

### Upsert a record
Update a record with the PATCH method and if the external Id doesn't exist, it will insert a new record.

```php
$body = [
    'Phone' => '555-555-5555',
    'External_Id__c' => 'XYZ1234'];

Tabapi::sobjects('Account',[
    'method' => 'patch',
    'body'   => $body]);
```

### Delete a record
Delete a record with the DELETE method.

```php
Tabapi::sobjects('Account/001i000000xxx', ['method' => 'delete']);
```

### XML format
Change the request/response format to XML with the `format` key or make it default in your config file.

```php
Tabapi::sobjects('Account',['format'=>'xml']);
```

## API Requests

With the exception of the `search` and `query` resources, all resources are requested dynamically using method overloading.

First, determine which resources you have access to by calling:
```php
Tabapi::resources();
```
Result:
```php
Array
(
    [sobjects] => /services/data/v30.0/sobjects
    [connect] => /services/data/v30.0/connect
    [query] => /services/data/v30.0/query
    [theme] => /services/data/v30.0/theme
    [queryAll] => /services/data/v30.0/queryAll
    [tooling] => /services/data/v30.0/tooling
    [chatter] => /services/data/v30.0/chatter
    [analytics] => /services/data/v30.0/analytics
    [recent] => /services/data/v30.0/recent
    [process] => /services/data/v30.0/process
    [identity] => https://login.salesforce.com/id/00Di0000000XXXXXX/005i0000000aaaaAAA
    [flexiPage] => /services/data/v30.0/flexiPage
    [search] => /services/data/v30.0/search
    [quickActions] => /services/data/v30.0/quickActions
    [appMenu] => /services/data/v30.0/appMenu
)
```
Next, call resources by referring to the specified key. For instance:
```php
Tabapi::theme();
```
or
```php
Tabapi::appMenu();
```

Resource urls can be extended by passing additional parameters into the first argument:
```php
Tabapi::sobjects('Account/describe/approvalLayouts/');
```

You can also add optional parameters to requests:
```php
Tabapi::theme(['format'=>'xml']);
```

### Additional API Requests

#### Refresh
If a refresh token is set, the server can refresh the access token on the user's behalf. Refresh tokens are only for the Web Server flow.
```php
Tabapi::refresh();
```
>If you need a refresh token, be sure to specify this under `access scope` in your [Connected App](#setting-up-connected-app). You can also specify this in your configuration file by adding `'scope' => 'full refresh_token'`. Setting scope access in the config file is optional, the default scope access is determined by your Salesforce org.

#### Revoke
This will revoke the authorization token. The session will continue to store a token, but it will become invalid.
```php
Tabapi::revoke();
```

#### Versions
Returns all currently supported versions. Includes the verison, label and link to each version's root:
```php
Tabapi::versions();
```

#### Resources
Returns list of available resources based on the logged in user's permission and API version.
```php
Tabapi::resources();
```

#### Identity
Returns information about the logged-in user.
```php
Tabapi::identity();
```

For a complete listing of API resources, refer to the [Force.com REST API Developer's Guide](http://www.salesforce.com/us/developer/docs/api_rest/api_rest.pdf)

### Custom Apex endpoints
If you create a custom API using Apex, you can use the `custom()` method for consuming them.
```php
Tabapi::custom('/myEndpoint');
```
Additional options and parameters can be passed in like this:
```php
Tabapi::custom('/myEndpoint', [
    'method' => 'post',
    'body' => ['foo' => 'bar'],
    'parameters' => ['flim' => 'flam']]);
```
> Read [Creating REST APIs using Apex REST](https://developer.salesforce.com/page/Creating_REST_APIs_using_Apex_REST) for more information.

### Raw Requests
If needed, you can make raw requests to an endpoint of your choice.
```php
Tabapi::get('/services/data/v20.0/endpoint');
Tabapi::head('/services/data/v20.0/endpoint');
Tabapi::post('/services/data/v20.0/endpoint', ['my'=>'param']);
Tabapi::put('/services/data/v20.0/endpoint', ['my'=>'param']);
Tabapi::patch('/services/data/v20.0/endpoint', ['my'=>'param']);
Tabapi::delete('/services/data/v20.0/endpoint');
```

### Raw response output
By default, this package will return the body of a response as either a deserialized JSON object or a SimpleXMLElement object.

There might be times, when you would rather handle this differently. To do this, simply use any format other than 'json' or 'xml' and the code will return a Guzzle response object.

```php
$response = Tabapi::sobjects($resource, ['format'=> 'none']);
$content = (string) $response->getBody(); // Guzzle response
```

### Event Listener
This package makes use of Guzzle's event listers
```php
Event::listen('Tabapi.response', function($request, $response) {
    dd((string) $response);
});
```

For more information about Guzzle responses and event listeners, refer to their [documentation](http://guzzle.readthedocs.org).