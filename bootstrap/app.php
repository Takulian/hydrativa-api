<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PetaniMiddleware;
use App\Http\Middleware\PemilikKebunMiddleware;
use App\Http\Middleware\PemilikAlamatMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin'=> AdminMiddleware::class,
            'petani'=> PetaniMiddleware::class,
            'pemilik-kebun' => PemilikKebunMiddleware::class,
            'pemilik-alamat' => PemilikAlamatMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
