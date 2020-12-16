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

use Chevere\Components\Message\Message;
use Chevere\Exceptions\Core\ArgumentCountException;
use Chevere\Exceptions\Core\InvalidArgumentException;
use Chevere\Exceptions\Core\UnexpectedValueException;
use Chevere\Exceptions\Parameter\ArgumentRequiredException;
use Chevere\Interfaces\Action\ActionInterface;
use Chevere\Interfaces\Parameter\ArgumentsInterface;
use Chevere\Interfaces\Parameter\ParametersInterface;
use Chevere\Interfaces\Workflow\StepInterface;
use ReflectionClass;

final class Step implements StepInterface
{
    private string $action;

    private array $arguments;

    private ParametersInterface $parameters;

    public function __construct(string $action)
    {
        /** @var class-string $action */
        $this->action = $action;

        try {
            $reflection = new ReflectionClass($this->action);
        } catch (\ReflectionException $e) {
            throw new InvalidArgumentException(
                (new Message("Class %action% doesn't exists"))
                    ->code('%action%', $this->action)
            );
        }
        if (!$reflection->implementsInterface(ActionInterface::class)) {
            throw new UnexpectedValueException(
                (new Message('Action %action% must implement %interface% interface'))
                    ->code('%action%', $this->action)
                    ->code('%interface%', ActionInterface::class)
            );
        }
        $this->parameters = $reflection->newInstance()->parameters();
        $this->arguments = [];
    }

    public function withArguments(mixed ...$namedArguments): StepInterface
    {
        /** @var array<string, mixed> $namedArguments */
        $new = clone $this;
        $new->assertArgumentsCount($namedArguments);
        $store = [];
        $missing = [];
        foreach ($new->parameters->getGenerator() as $name => $parameter) {
            $argument = $namedArguments[$name] ?? null;
            if (is_null($argument)) {
                $missing[] = $name;

                continue;
            }
            $store[$name] = $argument;
        }
        if ($missing !== []) {
            throw new ArgumentRequiredException(
                (new Message('Missing argument(s): %message%'))
                    ->code('%message%', implode(', ', $missing))
            );
        }
        $new->arguments = $store;

        return $new;
    }

    public function action(): string
    {
        return $this->action;
    }

    public function arguments(): array
    {
        return $this->arguments;
    }

    private function assertArgumentsCount(array $arguments): void
    {
        $count = count($arguments);
        if ($this->parameters->count() !== $count) {
            throw new ArgumentCountException(
                (new Message('Method %action% expects %interface% providing %parametersCount% arguments, %given% given'))
                    ->code('%action%', $this->action . '::run')
                    ->code('%interface%', ArgumentsInterface::class)
                    ->code('%parametersCount%', (string) $this->parameters->count())
                    ->code('%given%', (string) $count)
            );
        }
    }
}