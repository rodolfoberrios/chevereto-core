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

namespace Chevere\Tests\ExceptionHandler;

use Chevere\Exceptions\Core\ErrorException;
use Chevere\Components\ExceptionHandler\ExceptionRead;
use Chevere\Exceptions\Core\Exception;
use Chevere\Interfaces\ExceptionHandler\ExceptionReadInterface;
use Chevere\Components\Message\Message;
use LogicException;
use PHPUnit\Framework\TestCase;

final class ExceptionTest extends TestCase
{
    public function testConstructWithException(): void
    {
        $message = new Message('test');
        $code = 12345;
        $exceptionName = Exception::class;
        $throw = new $exceptionName($message, $code); // LINE
        $line = __LINE__ - 1;
        $exception = new ExceptionRead($throw);
        $this->assertSame($exceptionName, $exception->className());
        $this->assertEquals($message, $exception->message());
        $this->assertSame($code, $exception->code());
        $this->assertSame(__FILE__, $exception->file());
        $this->assertSame($line, $exception->line());
        $this->assertSame(1, $exception->severity());
        $this->assertSame('critical', $exception->loggerLevel());
        $this->assertSame('Fatal error', $exception->type());
        $this->assertIsArray($exception->trace());
    }

    public function testConstructWithErrorDefaultCode(): void
    {
        $code = ExceptionReadInterface::DEFAULT_ERROR_TYPE;
        $exceptionName = TestErrorException::class;
        $exception = new $exceptionName(new Message('test'));
        $normalized = new ExceptionRead($exception);
        $this->assertSame($code, $normalized->code());
    }

    public function testConstructWithErrorInvalidSeverity(): void
    {
        $exceptionName = TestErrorException::class;
        $exception = new $exceptionName(new Message('test'));
        $exception->setSeverity(12346664321);
        $this->expectException(LogicException::class);
        new ExceptionRead($exception);
    }
}

/**
 * A dummy ErrorException that allows to inject severity values.
 */
final class TestErrorException extends ErrorException
{
    public function setSeverity(int $severity): void
    {
        $this->severity = $severity;
    }
}