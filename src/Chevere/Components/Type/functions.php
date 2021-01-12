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

namespace Chevere\Components\Type;

use Chevere\Components\Message\Message;
use Chevere\Interfaces\Message\MessageInterface;
use Chevere\Interfaces\Type\TypeInterface;

function varType($var): string
{
    $type = gettype($var);

    return [
        'integer' => 'int',
        'double' => 'float',
        'boolean' => 'bool',
        'NULL' => 'null',
    ][$type] ?? $type;
}

function returnTypeExceptionMessage(string $expected, $provided): MessageInterface
{
    return (new Message('Expecting return type %expected%, type %provided% provided'))
        ->code('%expected%', $expected)
        ->code('%provided%', varType($provided));
}

function typeBool(): TypeInterface
{
    return new Type(Type::BOOL);
}

function typeInt(): TypeInterface
{
    return new Type(Type::INT);
}

function typeFloat(): TypeInterface
{
    return new Type(Type::FLOAT);
}

function typeString(): TypeInterface
{
    return new Type(Type::STRING);
}

function typeArray(): TypeInterface
{
    return new Type(Type::ARRAY);
}

function typeObject(): TypeInterface
{
    return new Type(Type::OBJECT);
}

function typeCallable(): TypeInterface
{
    return new Type(Type::CALLABLE);
}

function typeIterable(): TypeInterface
{
    return new Type(Type::ITERABLE);
}

function typeResource(): TypeInterface
{
    return new Type(Type::RESOURCE);
}

function typeNull(): TypeInterface
{
    return new Type(Type::NULL);
}
