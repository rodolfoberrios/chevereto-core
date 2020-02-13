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

use Chevere\Components\Message\Message;
use Chevere\Components\Type\Type;
use Chevere\Components\VarDump\Interfaces\ProcessorInterface;
use Chevere\Components\VarDump\Interfaces\VarDumperInterface;
use InvalidArgumentException;
use TypeError;
use function ChevereFn\varType;

abstract class AbstractProcessor implements ProcessorInterface
{
    protected VarDumperInterface $varDumper;

    /** @var string */
    protected string $info = '';

    final public function __construct(VarDumperInterface $varDumper)
    {
        $this->varDumper = $varDumper;
        $this->assertType();
        $this->process();
    }

    /**
     * @throws TypeError if the return value of VarDumpInterface::var() doesn't match the $var property type.
     */
    abstract protected function process(): void;

    // abstract public function type(): string;

    final private function assertType(): void
    {
        $type = new Type($this->type());
        if (!$type->validate($this->varDumper->dumpeable()->var())) {
            throw new InvalidArgumentException(
                (new Message('Instance of %className% expects a type %expected% for the return value of %method%, type %provided% returned'))
                    ->code('%className%', static::class)
                    ->code('%expected%', $this->type())
                    ->code('%method%', get_class($this->varDumper) . '::var()')
                    ->code('%provided%', varType($this->varDumper->dumpeable()->var()))
                    ->toString()
            );
        }
    }

    final public function typeHighlighted(): string
    {
        return $this->varDumper->formatter()
                ->highlight($this->type(), $this->type());
    }

    final public function highlightOperator(string $string): string
    {
        return $this->varDumper->formatter()
                ->highlight(
                    VarDumperInterface::_OPERATOR,
                    $string
                );
    }

    final public function highlightParentheses(string $string): string
    {
        return $this->varDumper->formatter()->emphasis("($string)");
    }

    final public function circularReference(): string
    {
        return '*circular reference*';
    }

    final public function maxDepthReached(): string
    {
        return '*max depth reached*';
    }

    final public function info(): string
    {
        return $this->info;
    }
}
