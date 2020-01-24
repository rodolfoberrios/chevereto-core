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

use Chevere\Components\App\Instances\ScreenContainerInstance;
use Chevere\Components\Screen\Interfaces\ContainerInterface;

function screen(): ContainerInterface
{
    return ScreenContainerInstance::get();
}