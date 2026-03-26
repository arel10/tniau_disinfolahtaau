<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'    => \App\Http\Middleware\AdminMiddleware::class,
            'block.ip' => \App\Http\Middleware\BlockedIpCheck::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\BlockedIpCheck::class,
            \App\Http\Middleware\SessionFingerprint::class,
            \App\Http\Middleware\TrackVisitor::class,
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->encryptCookies(except: ['googtrans', 'livechat_token']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
