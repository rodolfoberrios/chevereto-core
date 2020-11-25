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

namespace Chevere\Components\Parameter;

use Chevere\Components\Parameter\Traits\ParameterTrait;
use Chevere\Components\Type\Type;
use Chevere\Interfaces\Parameter\IntegerParameterInterface;
use Ds\Set;

final class IntegerParameter implements IntegerParameterInterface
{
    use ParameterTrait;

    private int $default = 0;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->assertName();
        $this->type = new Type(Type::INTEGER);
        $this->attributes = new Set();
    }

    public function withDefault(int $default): IntegerParameterInterface
    {
        $new = clone $this;
        $new->default = $default;

        return $new;
    }

    public function default(): int
    {
        return $this->default;
    }
}