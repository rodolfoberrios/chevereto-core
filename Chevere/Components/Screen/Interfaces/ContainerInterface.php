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

namespace Chevere\Components\Screen\Interfaces;

interface ContainerInterface
{
    public function __construct(ScreenInterface $runtime, ScreenInterface $debug);

    public function runtime(): ScreenInterface;

    public function debug(): ScreenInterface;
}