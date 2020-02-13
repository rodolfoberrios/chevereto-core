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

namespace Chevere\Components\VarDump\Processors;

use Chevere\Components\Type\Interfaces\TypeInterface;
use Chevere\Components\VarDump\Interfaces\HighlightInterface;

final class NullProcessor extends AbstractProcessor
{
    public function type(): string
    {
        return TypeInterface::NULL;
    }

    protected function process(): void
    {
        $this->varDumper->writer()->write(
            $this->typeHighlighted()
        );
    }
}
