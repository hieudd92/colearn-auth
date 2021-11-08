# Colearn Rpc Custom Authentication

## Installation

Install via composer
```bash
composer require colearn/auth
```

### Publish package assets

```bash
php artisan vendor:publish --provider="CoLearn\Auth\Providers\AuthServiceProvider"
```

## Config .env file

```
RPC_AUTH_QUEUE=
RPC_AUTH_URL=
RPC_AUTH_METHOD=
```

## Configure Auth guard

Inside the config/auth.php file you will need to make a few changes to configure Laravel to use the jwt guard to power your application authentication.

Make the following changes to the file:

```
'guards' => [
    'rpc' => [
        'driver' => 'rpc'
    ]
]
```

## Add some basic authentication routes

First let's add some routes in routes/api.php as follows:

```
Route::group([
	'prefix' => '/rpc',
	'middleware' => 'auth:rpc'
], function () {
	Route::post('auth-check', 'AuthController@rpcAuthCheck');
});
```