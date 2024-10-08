
# Laravel Dynamic Maintenance

A simple package to allow enabling maintenance mode for specific named routes.


## Installation

Require this package with composer. Works for Laravel 10 and above. Not tested for other versions.

```shell
composer require tanedaa/laravel-dynamic-maintenance
```
    
Publish the commands, middleware and custom maintenance view

```shell
php artisan vendor:publish --tag=laravel-dynamic-maintenance
```

Register the ```maintenance``` middleware to the routes you want to enable dynamic maintenance on.

```php
Route::middleware('maintenance')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
});
```
## Usage

Enable or disable maintenance mode for specific named routes by providing a comma-separated list of routes to the following commands. Wildcard routes are supported as well. Optionally, you can provide a secret key to bypass maintenance mode.

```shell
php artisan down:routes {routes} {--secret}
php artisan up:routes {routes} | all
```

To bypass maintenance mode, add a query parameter `secret` with the secret key you provided when enabling maintenance mode.

```shell
http://example.com/home?secret=mySecretKey
```

## Examples

```shell
php artisan down:routes welcome, home.contact
php artisan down:routes home.about --secret=mySecretKey
php artisan down.routes api/*
```

```shell
php artisan up:routes home.index, home.contact
php artisan up.routes api/*
php artisan up.routes all
```

## Configuration

Optionally, you can change the maintenance mode Title, Message and HTTP Code in the custom view for the dynamic maintenance routes by adding the following variables to your .env file:

```env
MAINTENANCE_TITLE = 'Service Unavailable'
MAINTENANCE_MESSAGE = 'Service Unavailable'
MAINTENANCE_CODE = 503
```