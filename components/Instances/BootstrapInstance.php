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

namespace Chevere\Components\Instances;

use Chevere\Components\Bootstrap\Interfaces\BootstrapInterface;
use Chevere\Components\Exception\LogicException;
use Chevere\Components\Message\Message;

/**
 * A container for the bootstrap.
 */
final class BootstrapInstance
{
    private static BootstrapInterface $instance;

    public function __construct(BootstrapInterface $bootstrap)
    {
        self::$instance = $bootstrap;
    }

    public static function get(): BootstrapInterface
    {
        if (!isset(self::$instance)) {
            throw new LogicException(
                new Message('No bootstrap instance present')
            );
        }

        return self::$instance;
    }
}
