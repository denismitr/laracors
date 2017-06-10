# Laravel Cross Origin Resource Sharing Middleware
This middleware is designed specifically for the Laravel framework and __RESTful APIs__ builded with it.
It allows requests made from frontends of other apps.

## Author
__Denis Mitrofanov__

[TheCollection.ru](https://thecollection.ru)

## Installation
--------

Use composer to install the package:

```bash
composer require denismitr/cors
```

Include in your `app/Http/Kernel.php` to the appropriate section
(all requests if all your routes are API or named middleware + API middleware group to make it work for every api route
or just named middleware):

Global middleware
-------
```php
/**
 * The application's global HTTP middleware stack.
 *
 * These middleware are run during every request to your application.
 *
 * @var array
 */
protected $middleware = [
    ...
    \Denismitr\Cors\CorsMiddleware::class
];
```
Named middleware
---------------
```php
/**
 * The application's route middleware.
 *
 * These middleware may be assigned to groups or used individually.
 *
 * @var array
 */
protected $routeMiddleware = [
    ...
    'cors' => \Denismitr\Cors\CorsMiddleware::class,
];

/**
 * The application's route middleware groups.
 *
 * @var array
 */
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