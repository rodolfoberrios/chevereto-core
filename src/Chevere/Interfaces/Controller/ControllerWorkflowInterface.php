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

namespace Chevere\Interfaces\Controller;

use Chevere\Interfaces\Workflow\WorkflowProviderInterface;
use Chevere\Interfaces\Workflow\WorkflowResponseInterface;

/**
 * Describes the component in charge of defining a Controller with Workflow.
 */
interface ControllerWorkflowInterface extends ControllerInterface, WorkflowProviderInterface
{
    public function getWorkflowResponse(mixed ...$namedData): WorkflowResponseInterface;
}
