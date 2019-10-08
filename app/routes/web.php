<?php

namespace App;

use Chevere\Route\Route;
use Chevere\Http\Method;

return [
    (new Route('/home'))
        ->withAddedMethod(
            (new Method('GET'))
                ->withController(Controllers\Home::class)
        )
        ->withName('homepageHtml'),
    (new Route('/'))
        ->withAddedMethod(
            (new Method('GET'))
                ->withController(Controllers\Index::class)
        )
        ->withName('homepage'),
    // ->addMiddleware(Middlewares\RoleAdmin::class)
    // ->addMiddleware(Middlewares\RoleBanned::class),
    (new Route('/cache/{llave?}-{cert}-{user?}'))
        ->withWhere('llave', '[0-9]+')
        ->withAddedMethod(
            (new Method('GET'))
                ->withController(Controllers\Cache::class)
        )
        ->withAddedMethod(
            (new Method('POST'))
                ->withController(Controllers\Cache::class)
        )
        ->withName('cache'),
];
