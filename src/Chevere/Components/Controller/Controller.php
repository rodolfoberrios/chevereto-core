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

namespace Chevere\Components\Controller;

use Chevere\Components\Action\Action;
use Chevere\Components\Dependent\Traits\DependentTrait;
use Chevere\Components\Message\Message;
use Chevere\Components\Parameter\Arguments;
use Chevere\Components\Parameter\Parameters;
use Chevere\Components\Type\Type;
use Chevere\Exceptions\Core\InvalidArgumentException;
use Chevere\Exceptions\Core\LogicException;
use Chevere\Interfaces\Controller\ControllerInterface;
use Chevere\Interfaces\Dependent\DependentInterface;
use Chevere\Interfaces\Parameter\ArgumentsInterface;
use Chevere\Interfaces\Parameter\ParametersInterface;
use Chevere\Interfaces\Type\TypeInterface;

abstract class Controller extends Action implements ControllerInterface, DependentInterface
{
    use DependentTrait;

    protected ParametersInterface $contextParameters;

    protected ArgumentsInterface $contextArguments;

    protected TypeInterface $parametersType;

    public function __construct()
    {
        parent::__construct();

        $this->contextParameters = $this->getContextParameters();
        $this->parametersType = new Type(self::PARAMETER_TYPE);
        $this->assertParametersType();
    }

    public function getContextParameters(): ParametersInterface
    {
        return new Parameters();
    }

    final public function withContextArguments(mixed ...$namedArguments): self
    {
        $new = clone $this;
        $new->contextArguments = new Arguments(
            $new->contextParameters,
            ...$namedArguments
        );

        return $new;
    }

    final public function contextArguments(): ArgumentsInterface
    {
        return $this->contextArguments;
    }

    final public function hasContextArguments(): bool
    {
        return isset($this->contextArguments);
    }

    final public function assertContextArguments(): void
    {
        if (count($this->contextParameters) > 0 && ! isset($this->contextArguments)) {
            throw new LogicException(
                message: new Message('Missing context arguments')
            );
        }
    }

    final public function contextParameters(): ParametersInterface
    {
        return $this->contextParameters;
    }

    private function assertParametersType(): void
    {
        $invalid = [];
        foreach ($this->parameters()->getGenerator() as $name => $parameter) {
            if ($parameter->type()->validator() !== $this->parametersType->validator()) {
                $invalid[] = $name;
            }
        }
        if ($invalid !== []) {
            throw new InvalidArgumentException(
                (new Message('Parameter %parameters% must be of type %type% for controller %className%.'))
                    ->code('%parameters%', implode(', ', $invalid))
                    ->strong('%type%', $this->parametersType->typeHinting())
                    ->strong('%className%', static::class)
            );
        }
    }
}
