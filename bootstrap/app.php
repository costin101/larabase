<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'admin'              => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 1. Catch Spatie's Role/Permission Exception
        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            throw new NotFoundHttpException();
        });

        // 2. Catch standard Laravel Authorization Exception (Gate::denies)
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            throw new NotFoundHttpException();
        });
    })->create();
