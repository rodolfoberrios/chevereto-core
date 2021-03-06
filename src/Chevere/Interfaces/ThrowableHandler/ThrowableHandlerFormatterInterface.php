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

namespace Chevere\Interfaces\ThrowableHandler;

use Chevere\Interfaces\VarDump\VarDumpFormatterInterface;

/**
 * Describes the component in charge of formatting a throwable handler document.
 */
interface ThrowableHandlerFormatterInterface
{
    /**
     * Provides access to the VarDumpFormatterInterface instance.
     */
    public function varDumpFormatter(): VarDumpFormatterInterface;

    /**
     * Get a new object implementing the VarDumpFormatterInterface.
     */
    public function getVarDumpFormatter(): VarDumpFormatterInterface;

    /**
     * Returns the template used for each trace entry.
     */
    public function getTraceEntryTemplate(): string;

    /**
     * Returns formatted horizontal rule.
     */
    public function getHr(): string;

    /**
     * Returns formatted line break.
     */
    public function getLineBreak(): string;

    /**
     * Returns `$value` formatted as wrapped link.
     */
    public function wrapLink(string $value): string;

    /**
     * Returns `$value` formatted as section title.
     */
    public function wrapSectionTitle(string $value): string;

    /**
     * Returns `$value` formatted as title.
     */
    public function wrapTitle(string $value): string;
}
