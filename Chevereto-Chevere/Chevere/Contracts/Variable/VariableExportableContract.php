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

namespace Chevere\Contracts\Variable;

use Chevere\Components\Variable\Exceptions\VariableIsResourceException;

interface VariableExportableContract
{
    /**
     * Creates a new instance.
     *
     * @throws VariableIsResourceException if $var contains resource
     */
    public function __construct($var);

    /**
     * Provides access to $var.
     */
    public function var();

    /**
     * Returns var_export($var, true).
     */
    public function toExport();

    /**
     * Returns serialize() on $var.
     */
    public function toSerialize(): string;
}