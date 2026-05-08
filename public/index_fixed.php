<?php

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check For The Test Environment
|--------------------------------------------------------------------------
|
| If we are running unit tests we need to bypass some of the normal
| bootstrapping since we don't need to resolve a lot of the dependencies
| and we don't need to boot up a bunch of framework features.
|
*/

if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'testing') {
    require __DIR__.'/../vendor/autoload.php';

    $app = require_once __DIR__.'/../bootstrap/app.php';

    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    exit($app->make(Illuminate\Contracts\Console\Kernel::class)->call('list', $argv));
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

// Bypass platform check for PHP 8.0 compatibility
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
