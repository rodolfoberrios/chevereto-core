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

namespace Chevere\Components\Exception;

use Chevere\Components\Exception\Traits\ExceptionTrait;
use Chevere\Components\Message\Interfaces\MessageInterface;
use Chevere\Components\Message\Message;
use Throwable;

/**
 * Exception thrown if a callback refers to an undefined method or if some arguments are missing.
 */
class BadMethodCallException extends \BadMethodCallException
{
    use ExceptionTrait;

    public function __construct(MessageInterface $message = null, int $code = 0, Throwable $previous = null)
    {
        $this->_message = $message ?? new Message('');
        parent::__construct($this->_message->toString(), $code, $previous);
    }
}
