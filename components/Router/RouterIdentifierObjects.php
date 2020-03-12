<?php

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevere.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Chevere\Components\Router;

use Chevere\Components\Route\Interfaces\RoutePathInterface;
use Chevere\Components\Router\Interfaces\RouteIdentifierInterface;
use SplObjectStorage;

final class RouterIdentifierObjects extends SplObjectStorage
{
    public function append(RouteIdentifierInterface $routeIdentifier, RoutePathInterface $routePath)
    {
        return parent::attach($routeIdentifier, $routePath);
    }

    public function current(): RouteIdentifierInterface
    {
        return parent::current();
    }

    public function getInfo(): RoutePathInterface
    {
        return  parent::getInfo();
    }
}