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

namespace Chevere\Components\Router\Route;

use Chevere\Components\Message\Message;
use Chevere\Components\Str\StrBool;
use Chevere\Exceptions\Router\Route\RouteWildcardInvalidException;
use Chevere\Interfaces\Router\Route\RouteWildcardInterface;
use Chevere\Interfaces\Router\Route\RouteWildcardMatchInterface;

final class RouteWildcard implements RouteWildcardInterface
{
    private string $name;

    private RouteWildcardMatchInterface $match;

    public function __construct(string $name, RouteWildcardMatchInterface $match)
    {
        $this->name = $name;
        $this->assertName();
        $this->match = $match;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function match(): RouteWildcardMatchInterface
    {
        return $this->match;
    }

    private function assertName(): void
    {
        if ((new StrBool($this->name))->startsWithCtypeDigit()) {
            throw new RouteWildcardInvalidException(
                (new Message('String %string% must not start with a numeric value'))
                    ->code('%string%', $this->name)
            );
        }
        if (! preg_match(RouteWildcardInterface::ACCEPT_CHARS_REGEX, $this->name)) {
            throw new RouteWildcardInvalidException(
                (new Message('String %string% must contain only alphanumeric and underscore characters'))
                    ->code('%string%', $this->name)
            );
        }
    }
}
