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

namespace Chevere\Tests\Spec\Specs;

use function Chevere\Components\Filesystem\dirForPath;
use Chevere\Components\Http\Methods\GetMethod;
use Chevere\Components\Router\Route\RouteEndpoint;
use Chevere\Components\Spec\Specs\RouteEndpointSpec;
use Chevere\Tests\Spec\_resources\src\TestController;
use PHPUnit\Framework\TestCase;

final class RouteEndpointSpecTest extends TestCase
{
    public function testConstruct(): void
    {
        $specDir = dirForPath('/spec/group/route-name/');
        $routeEndpoint = new RouteEndpoint(new GetMethod(), new TestController());
        $spec = new RouteEndpointSpec($specDir, $routeEndpoint);
        $specPathJson = $specDir->path()->toString() .
            $routeEndpoint->method()->name() . '.json';
        $this->assertSame($specPathJson, $spec->jsonPath());
        $this->assertSame(
            [
                'name' => $routeEndpoint->method()->name(),
                'spec' => $specDir->path()->toString() . $routeEndpoint->method()->name() . '.json',
                'description' => $routeEndpoint->method()->description(),
                'parameters' => $routeEndpoint->parameters(),
            ],
            $spec->toArray()
        );
    }
}
