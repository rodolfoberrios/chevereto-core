<?php

declare(strict_types=1);

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevereto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chevere\Runtime\Sets;

use RuntimeException;
use Chevere\Message\Message;
use Chevere\Runtime\Traits\RuntimeSet;
use Chevere\Contracts\Runtime\SetContract;

class SetDefaultCharset implements SetContract
{
    use RuntimeSet;

    public function set(): void
    {
        if (!@ini_set('default_charset', $this->value)) {
            throw new RuntimeException(
                (new Message('Unable to set %s %v.'))
                    ->code('%s', 'default_charset')
                    ->code('%v', $this->value)
                    ->toString()
            );
        }
    }
}