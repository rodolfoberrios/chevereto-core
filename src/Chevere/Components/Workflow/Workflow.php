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

namespace Chevere\Components\Workflow;

use Chevere\Components\Dependent\Dependencies;
use Chevere\Components\Message\Message;
use Chevere\Components\Parameter\Parameters;
use Chevere\Exceptions\Core\InvalidArgumentException;
use Chevere\Exceptions\Core\OutOfBoundsException;
use Chevere\Exceptions\Core\OverflowException;
use Chevere\Exceptions\Core\TypeException;
use Chevere\Interfaces\Action\ActionInterface;
use Chevere\Interfaces\Dependent\DependenciesInterface;
use Chevere\Interfaces\Dependent\DependentInterface;
use Chevere\Interfaces\Parameter\ParameterInterface;
use Chevere\Interfaces\Parameter\ParametersInterface;
use Chevere\Interfaces\Workflow\StepInterface;
use Chevere\Interfaces\Workflow\WorkflowInterface;
use Ds\Map;
use Ds\Vector;
use Generator;
use function Safe\preg_match;
use Throwable;
use TypeError;

final class Workflow implements WorkflowInterface
{
    private string $name;

    private Map $map;

    private Vector $steps;

    private ParametersInterface $parameters;

    private Map $vars;

    private Map $expected;

    private DependenciesInterface $dependencies;

    public function __construct(StepInterface ...$steps)
    {
        $this->map = new Map();
        $this->steps = new Vector();
        $this->parameters = new Parameters();
        $this->vars = new Map();
        $this->expected = new Map();
        $this->dependencies = new Dependencies();
        $this->putAdded(...$steps);
    }

    public function count(): int
    {
        return $this->steps->count();
    }

    public function withAdded(StepInterface ...$steps): WorkflowInterface
    {
        $new = clone $this;
        $new->putAdded(...$steps);

        return $new;
    }

    public function withAddedBefore(string $before, StepInterface ...$step): WorkflowInterface
    {
        $new = clone $this;
        $new->assertHasStepByName($before);
        foreach ($step as $name => $stepEl) {
            $new->handleStepDependencies($stepEl);
            $name = (string) $name;
            $new->putMap($name, $stepEl);
            $new->steps->insert($new->getPosByName($before), $name);
        }

        return $new;
    }

    public function withAddedAfter(string $after, StepInterface ...$step): WorkflowInterface
    {
        $new = clone $this;
        $new->assertHasStepByName($after);
        foreach ($step as $name => $stepEl) {
            $new->handleStepDependencies($stepEl);
            $name = (string) $name;
            $new->putMap($name, $stepEl);
            $new->steps->insert($new->getPosByName($after) + 1, $name);
        }

        return $new;
    }

    public function has(string $step): bool
    {
        return $this->map->hasKey($step);
    }

    /**
     * @throws TypeException
     * @throws OutOfBoundsException
     */
    public function get(string $step): StepInterface
    {
        try {
            return $this->map->get($step);
        }
        // @codeCoverageIgnoreStart
        catch (TypeError $e) {
            throw new TypeException(previous: $e);
        }
        // @codeCoverageIgnoreEnd
        catch (\OutOfBoundsException $e) {
            throw new OutOfBoundsException(
                (new Message('Step %name% not found'))
                    ->code('%name%', $step)
            );
        }
    }

    public function dependencies(): DependenciesInterface
    {
        return $this->dependencies;
    }

    public function parameters(): ParametersInterface
    {
        return $this->parameters;
    }

    public function order(): array
    {
        return $this->steps->toArray();
    }

    public function hasVar(string $variable): bool
    {
        return $this->vars->hasKey($variable);
    }

    /**
     * @throws TypeException
     * @throws OutOfBoundsException
     */
    public function getVar(string $variable): array
    {
        try {
            return $this->vars->get($variable);
        }
        // @codeCoverageIgnoreStart
        catch (TypeError $e) {
            throw new TypeException(previous: $e);
        }
        // @codeCoverageIgnoreEnd
        catch (\OutOfBoundsException $e) {
            throw new OutOfBoundsException(
                (new Message('Variable %variable% not found'))
                    ->code('%variable%', $variable)
            );
        }
    }

    /**
     * @throws TypeException
     * @throws OutOfBoundsException
     */
    public function getExpected(string $step): array
    {
        try {
            return $this->expected->get($step);
        }
        // @codeCoverageIgnoreStart
        catch (TypeError $e) {
            throw new TypeException(previous: $e);
        }
        // @codeCoverageIgnoreEnd
        catch (\OutOfBoundsException $e) {
            throw new OutOfBoundsException(
                (new Message('Step %step% not found'))
                    ->code('%step%', $step)
            );
        }
    }

    public function getGenerator(): Generator
    {
        foreach ($this->steps as $step) {
            yield $step => $this->get($step);
        }
    }

    private function putAdded(StepInterface ...$steps): void
    {
        foreach ($steps as $name => $step) {
            $this->handleStepDependencies($step);
            $name = (string) $name;
            $this->putMap($name, $step);
            $this->steps->push($name);
        }
    }

    private function handleStepDependencies(StepInterface $step): void
    {
        $actionName = $step->action();
        /** @var ActionInterface $action */
        $action = new $actionName();
        if ($action instanceof DependentInterface) {
            $this->dependencies = $this->dependencies
                ->withMerge($action->dependencies());
        }
    }

    private function putMap(string $name, StepInterface $step): void
    {
        $this->assertNoOverflow($name);
        $this->setParameters($name, $step);
        $this->map->put($name, $step);
    }

    private function assertNoOverflow(string $name): void
    {
        if ($this->map->hasKey($name)) {
            throw new OverflowException(
                (new Message('Step name %name% has been already added.'))
                    ->code('%name%', $name)
            );
        }
    }

    private function setParameters(string $name, StepInterface $step): void
    {
        $action = $step->action();
        /** @var ActionInterface $action */
        $action = new $action();
        $parameters = $action->parameters();
        foreach ($step->arguments() as $argument) {
            try {
                preg_match(self::REGEX_PARAMETER_REFERENCE, (string) $argument, $matches);
                // @codeCoverageIgnoreEnd
                if ($matches !== []) {
                    /** @var array $matches */
                    $this->putParameter($matches[1], $parameters->get($matches[1]));
                    $this->vars->put($argument, [$matches[1]]);
                } elseif (preg_match(self::REGEX_STEP_REFERENCE, (string) $argument, $matches)) {
                    /** @var array $matches */
                    $this->assertStepExists($name, $matches);
                    $expected = $this->expected->get($matches[1], []);
                    $expected[] = $matches[2];
                    $this->expected->put($matches[1], $expected);
                    $this->vars->put($argument, [$matches[1], $matches[2]]);
                }
            } catch (Throwable $e) {
                throw new InvalidArgumentException(
                    previous: $e,
                    message: (new Message('Step %name%: %message%'))
                        ->strong('%name%', $name)
                        ->code('%message%', $e->getMessage())
                );
            }
        }
    }

    private function assertHasStepByName(string $step): void
    {
        if (! $this->map->hasKey($step)) {
            throw new OutOfBoundsException(
                (new Message("Task name %name% doesn't exists"))
                    ->code('%name%', $step)
            );
        }
    }

    private function getPosByName(string $step): int
    {
        /** @var int */
        return $this->steps->find($step);
    }

    private function putParameter(string $name, ParameterInterface $parameter): void
    {
        if ($this->parameters->has($name)) {
            $existent = $this->parameters->get($name);
            if ($existent::class !== $parameter::class) {
                throw new InvalidArgumentException(
                    message: (new Message('Expecting type %expected% for parameter %name%, type %provided% provided'))
                        ->code('%expected%', $existent::class)
                        ->strong('%name%', $name)
                        ->code('%provided%', $parameter::class)
                );
            }
            $this->parameters = $this->parameters
                ->withModify(...[
                    $name => $parameter,
                ]);

            return;
        }
        $this->parameters = $this->parameters
            ->withAdded(...[
                $name => $parameter,
            ]);
    }

    private function assertStepExists(string $step, array $matches): void
    {
        if (! $this->map->hasKey($matches[1])) {
            throw new OutOfBoundsException(
                (new Message("Referenced parameter %previous%:%parameter% doesn't exists"))
                    ->code('%step%', $step)
                    ->code('%parameter%', (string) $matches[2])
                    ->code('%previous%', (string) $matches[1])
            );
        }
    }
}
