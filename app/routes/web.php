<?php

namespace App;

use Chevere\Components\Route\Route;
use Chevere\Components\Http\Method;

return [
    (new Route('/home/{wea}/{cosa}'))
        ->withAddedMethod(
            (new Method('GET'))
                ->withControllerName(Controllers\Home::class)
        )
        ->withName('web.home'),
    (new Route('/2'))
        ->withAddedMethod(
            (new Method('GET'))
                ->withControllerName(Controllers\Index::class)
        )
        ->withName('web.2'),
    // ->addMiddleware(Middlewares\RoleAdmin::class)
    // ->addMiddleware(Middlewares\RoleBanned::class),
    // (new Route('/cache/{llave?}-{cert}-{user?}'))
    //     ->withWhere('llave', '[0-9]+')
    //     ->withAddedMethod(
    //         (new Method('GET'))
    //             ->withControllerName(Controllers\Cache::class)
    //     )
    //     ->withAddedMethod(
    //         (new Method('POST'))
    //             ->withControllerName(Controllers\Cache::class)
    //     )
    //     ->withName('cache'),
];
