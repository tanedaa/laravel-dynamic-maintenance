
# Laravel Dynamic Maintenance

A simple package to allow enabling maintenance mode for specific named routes.


## Installation

Require this package with composer

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

Enable or disable maintenance mode for specific named routes by providing a comma-separated list of routes to the following commands:

```shell
php artisan down:routes {routes}
php artisan up:routes {routes} | all
```
## Configuration

Optionally, you can change the maintenance mode Title, Message and HTTP Code for the dynamic maintenance routes by adding the following variables to your .env file:

```env
MAINTENANCE_TITLE = 'Service Unavailable'
MAINTENANCE_MESSAGE = 'Service Unavailable'
MAINTENANCE_CODE = 503
```