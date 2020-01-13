<?php

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevereto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Chevere\Components\VarDump\Outputters;

use Chevere\Components\VarDump\Interfaces\DumperInterface;
use Chevere\Components\VarDump\Interfaces\OutputterInterface;
use Chevere\Components\VarDump\VarDump;
use function ChevereFn\stringStartsWith;

abstract class AbstractOutputter implements OutputterInterface
{
    protected string $output = '';

    protected DumperInterface $dumper;

    /**
     * {@inheritdoc}
     */
    abstract public function prepare(): OutputterInterface;

    abstract public function print(): void;

    /**
     * {@inheritdoc}
     */
    final public function withDumper(DumperInterface $dumper): OutputterInterface
    {
        $new = clone $this;
        $new->dumper = $dumper;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    final public function dumper(): DumperInterface
    {
        return $this->dumper;
    }

    /**
     * {@inheritdoc}
     */
    final public function process(): OutputterInterface
    {
        $this->prepare();
        $this->handleClass();
        $this->output .= $this->dumper->formatter()
            ->applyWrap('_function', $this->dumper->debugBacktrace()[$this->dumper::OFFSET]['function'] . '()');
        $this->handleFile();
        $this->output .= "\n\n";
        $this->handleArgs();
        $this->output = trim($this->output);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function toString(): string
    {
        return $this->output;
    }

    final private function handleClass(): void
    {
        if (isset($this->dumper->debugBacktrace()[1]['class'])) {
            $class = $this->dumper->debugBacktrace()[$this->dumper::OFFSET]['class'];
            if (stringStartsWith('class@anonymous', $class)) {
                $class = explode('0x', $class)[0];
            }
            $this->output .= $this->dumper->formatter()
                ->applyWrap('_class', $class) . $this->dumper->debugBacktrace()[$this->dumper::OFFSET]['type'];
        }
    }

    final private function handleFile(): void
    {
        if (isset($this->dumper->debugBacktrace()[0]['file'])) {
            $this->output .= "\n" . $this->dumper->formatter()
                ->applyWrap('_file', $this->dumper->debugBacktrace()[0]['file'] . ':' . $this->dumper->debugBacktrace()[0]['line']);
        }
    }

    final private function handleArgs(): void
    {
        $pos = 1;
        foreach ($this->dumper->vars() as $value) {
            $this->appendArg($pos, $value);
            ++$pos;
        }
    }

    final private function appendArg(int $pos, $value): void
    {
        $varDump = (new VarDump($value, $this->dumper->formatter()))
            ->process();
        $this->output .= 'Arg#' . $pos . ' ' . $varDump->toString() . "\n\n";
    }
}