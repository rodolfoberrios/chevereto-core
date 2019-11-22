<?php

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevereto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Chevere\Tests\Router;

use Chevere\Components\Controller\ControllerName;
use Chevere\Components\Http\Method;
use Chevere\Components\Route\PathUri;
use Chevere\Components\Route\Route;
use Chevere\Components\Router\Exceptions\RouteableException;
use Chevere\Components\Router\Routeable;
use Chevere\TestApp\App\Controllers\TestController;
use PHPUnit\Framework\TestCase;

final class RouteableTest extends TestCase
{
    public function testConstructInvalidArgument(): void
    {
        $this->expectException(RouteableException::class);
        new Routeable(
          new Route(
            new PathUri('/test')
          )
        );
    }

    public function testConstruct(): void
    {
        $route = (new Route(new PathUri('/test')))
          ->withAddedMethod(
            new Method('GET'),
            new ControllerName(TestController::class)
          );
        $routeable = new Routeable($route);
        $this->assertSame($route, $routeable->route());
    }
}
