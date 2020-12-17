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

namespace Chevere\Tests\Instances;

use Chevere\Components\Instances\VarDumpInstance;
use function Chevere\Components\VarDump\varDumpPlain;
use Chevere\Exceptions\Core\LogicException;
use PHPUnit\Framework\TestCase;

final class VarDumpInstanceTest extends TestCase
{
    public function testNoConstruct(): void
    {
        $this->expectException(LogicException::class);
        VarDumpInstance::get();
    }

    public function testConstruct(): void
    {
        $varDump = varDumpPlain();
        $instance = new VarDumpInstance($varDump);
        $this->assertSame($varDump, $instance::get());
    }
}
