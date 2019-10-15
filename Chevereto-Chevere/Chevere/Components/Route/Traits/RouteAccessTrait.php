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

namespace Chevere\Components\Route\Traits;

use Chevere\Contracts\Route\RouteContract;

trait RouteAccessTrait
{
    /** @var RouteContract */
    private $route;

    public function hasRoute(): bool
    {
        return isset($this->route);
    }

    public function route(): RouteContract
    {
        return $this->route;
    }
}