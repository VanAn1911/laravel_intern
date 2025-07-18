<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckStatus;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //sử dung toàn cục 
        $middleware->append(CheckStatus::class);
        // sử dụng alias để đăng ký middleware với tên tùy chỉnh 
        $middleware->alias([
        'check.status' => CheckStatus::class,
        'is_admin' => IsAdmin::class,
        'is_user' => IsUser::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
