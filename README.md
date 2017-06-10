# __LARACORS__
## Laravel Cross Origin Resource Sharing Middleware
This middleware is designed specifically for the Laravel and Lumen frameworks and the __RESTful APIs__ builded with them.
It allows requests to made from JS frontends of other apps.

## Author
__Denis Mitrofanov__

[TheCollection.ru](https://thecollection.ru)

## Installation
--------

Use composer to install the package:

```bash
composer require denismitr/laracors
```

### Laravel

Add to ```config/app.php```:

```php
'providers' => [
    ...
    Denismitr\Laracors\Cors::class,
],
```

Include in your `app/Http/Kernel.php` to the appropriate section
(all requests if all your routes are API or named middleware + API middleware group to make it work for every api route
or just named middleware):

Global middleware
-------
```php
protected $middleware = [
    ...
    \Denismitr\Laracors\Cors::class
];
```

Publish the config file:
```php
php artisan vendor:publish  --provider="Denismitr\Laracors\LaravelCorsServiceProvider"
```

Edit the ```config/laracors.php``` file to your needs.

```
Named middleware
---------------
```php

protected $routeMiddleware = [
    ...
    'cors' => \Denismitr\Laracors\LaravelCorsServiceProvider::class,
];

protected $middlewareGroups = [
    'web' => [
        ...
    ],

    'api' => [
        ...
        'cors'
    ],
];
```

Middleware parameters
```php
Route::put('post/{id}', function ($id) {
    //
})->middleware('cors:get,post,put');
```

## Lumen

Add the following lines to ```bootstrap/app.php```:

```php
$app->register('Denismitr\Laracors\LumenCorsServiceProvider');
```

```php
$app->middleware([
    .....
    'Denismitr\Laracors\LumenCorsServiceProvider',
]);
```