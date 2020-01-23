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

namespace Chevere\Components\VarDump;

use Chevere\Components\App\Instances\BootstrapInstance;
use Chevere\Components\VarDump\Dumpers\ConsoleDumper;
use Chevere\Components\VarDump\Dumpers\HtmlDumper;

/**
 * Context-aware dumper.
 */
final class Dumper
{
    public function __construct(...$vars)
    {
        $dumped =
            (BootstrapInstance::get()->isCli() ? new ConsoleDumper() : new HtmlDumper())
                ->withVars(...$vars)
                ->outputter()
                ->toString();

        screen()->runtime()->attachNl($dumped)->display();
    }
}
