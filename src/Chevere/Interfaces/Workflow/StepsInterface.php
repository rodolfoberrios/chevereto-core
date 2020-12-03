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

namespace Chevere\Interfaces\Workflow;

use Chevere\Exceptions\Core\OverflowException;
use Chevere\Interfaces\DataStructures\MappedInterface;
use Generator;

/**
 * Describes the component in charge of collecting stepped tasks.
 */
interface StepsInterface extends MappedInterface
{
    /**
     * @throws OverflowException
     */
    public function withAdded(StepInterface $step, TaskInterface $task): StepsInterface;

    /**
     * @return Generator<string, TaskInterface>
     */
    public function getGenerator(): Generator;
}
