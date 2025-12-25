<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// check if app is in maintenance mode
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// register composer autoloader
require __DIR__.'/../vendor/autoload.php';

// bootstrap laravel and handle request
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
