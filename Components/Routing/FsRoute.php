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

namespace Chevere\Components\Routing;

use Chevere\Interfaces\Filesystem\DirInterface;
use Chevere\Interfaces\Route\RouteDecoratorInterface;
use Chevere\Interfaces\Route\RoutePathInterface;
use Chevere\Interfaces\Routing\FsRouteInterface;

/**
 * @codeCoverageIgnore
 */
final class FsRoute implements FsRouteInterface
{
    private DirInterface $dir;

    private RoutePathInterface $path;

    private RouteDecoratorInterface $decorator;

    public function __construct(
        DirInterface $dir,
        RoutePathInterface $path,
        RouteDecoratorInterface $decorator
    ) {
        $this->dir = $dir;
        $this->path = $path;
        $this->decorator = $decorator;
    }

    public function dir(): DirInterface
    {
        return $this->dir;
    }

    public function routePath(): RoutePathInterface
    {
        return $this->path;
    }

    public function routeDecorator(): RouteDecoratorInterface
    {
        return $this->decorator;
    }
}