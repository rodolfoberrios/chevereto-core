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

namespace Chevere\Components\Hooks\Interfaces;

use Chevere\Components\Hooks\HookAnchors;
use Chevere\Components\Hooks\HooksRunner;

interface HookableInterface
{
    /**
     * Returns the declared hook anchors.
     */
    public static function getHookAnchors(): HookAnchors;

    /**
     * Attach the hooks runner for this hookable.
     */
    public function withHooksRunner(HooksRunner $hooksRunner): HookableInterface;

    /**
     * Run hooks for the given anchor (if-any).
     *
     * @param string $anchor Hook anchor
     * @param string $argument An argument to pass to hooks queue
     */
    public function hook(string $anchor, &$argument): void;
}
