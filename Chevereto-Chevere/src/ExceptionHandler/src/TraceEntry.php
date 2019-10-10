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

namespace Chevere\ExceptionHandler\src;

use ReflectionMethod;

use const Chevere\PATH;
use Chevere\App\App;
use Chevere\Path\Path;
use Chevere\VarDump\Formatters\DumperFormatter;
use Chevere\VarDump\Formatters\PlainFormatter;
use Chevere\VarDump\VarDump;

use function ChevereFn\pathNormalize;
use function ChevereFn\stringReplaceFirst;
use function ChevereFn\stringStartsWith;

/**
 * TraceEntry prepares the exception trace for being used with Stack.
 */
final class TraceEntry
{
    /** @var array Exception trace entry */
    private $entry;

    /** @var int Key for the passed trace entry */
    private $key;

    /** @var string Plain representation of the entry arguments */
    private $plainArgs;

    /** @var string Rich representation of the entry arguments (colored) */
    private $richArgs;

    /** @var array */
    private $rich;

    /** @var array */
    private $plain;

    public function __construct(array $entry, int $key)
    {
        $this->entry = $entry;
        $this->key = $key;
        $this->handleProcessMissingClassFile();
        $this->handleSetEntryArguments();
        $this->handleProcessAnonClass();
        $this->handleProcessCoreAutoloader();
        $this->handleProcessNormalizeFile();
        $this->setPlain();
        $this->setRich();
    }

    public function rich(): array
    {
        return $this->rich;
    }

    public function plain(): array
    {
        return $this->plain;
    }

    private function setPlain(): void
    {
        $this->plain = [
            '%x%' => ($this->key & 1) ? 'pre--even' : null,
            '%i%' => $this->key,
            '%f%' => $this->entry['file'] ?? null,
            '%l%' => $this->entry['line'] ?? null,
            '%fl%' => isset($this->entry['file']) ? ($this->entry['file'] . ':' . $this->entry['line']) : null,
            '%c%' => $this->entry['class'] ?? null,
            '%t%' => $this->entry['type'] ?? null,
            '%m%' => $this->entry['function'],
            '%a%' => $this->plainArgs ?? null,
        ];
    }

    private function setRich(): void
    {
        $dumperFormatter = new DumperFormatter();
        $this->rich = $this->plain;
        array_pop($this->rich);
        foreach ([
            '%f%' => VarDump::_FILE,
            '%l%' => VarDump::_FILE,
            '%fl%' => VarDump::_FILE,
            '%c%' => VarDump::_CLASS,
            '%t%' => VarDump::_OPERATOR,
            '%m%' => VarDump::_FUNCTION,
        ] as $k => $v) {
            $wrapper = $dumperFormatter->wrap($v, (string) $this->plain[$k]);
            $this->rich[$k] = isset($this->plain[$k]) ? $wrapper : null;
        }
        $this->rich['%a%'] = $this->richArgs;
    }

    private function handleProcessMissingClassFile()
    {
        if (!array_key_exists('file', $this->entry) && isset($this->entry['class'])) {
            $this->processMissingClassFile();
        }
    }

    private function processMissingClassFile()
    {
        $reflector = new ReflectionMethod($this->entry['class'], $this->entry['function']);
        $filename = $reflector->getFileName();
        if (false !== $filename) {
            $this->entry['file'] = $filename;
            $this->entry['line'] = $reflector->getStartLine();
        }
    }

    private function handleSetEntryArguments()
    {
        if (isset($this->entry['args']) && is_array($this->entry['args'])) {
            $this->setFrameArguments();
        }
    }

    private function setFrameArguments()
    {
        $plainVarDump = new VarDump(new PlainFormatter());
        $richVarDump = new VarDump(new DumperFormatter());

        $this->plainArgs = "\n";
        $this->richArgs = "\n";
        foreach ($this->entry['args'] as $k => $expression) {
            $aux = 'Arg#' . ($k + 1) . ' ';
            $plainVarDump = $plainVarDump
                ->withDontDump([App::class])
                ->withDump($expression, 0);
            $richVarDump = $richVarDump
                ->withDontDump([App::class])
                ->withDump($expression, 0);
            $this->plainArgs .= $aux . $plainVarDump->toString() . "\n";
            $this->richArgs .= $aux . $richVarDump->toString() . "\n";
        }
        $this->trimTrailingNl($this->plainArgs);
        $this->trimTrailingNl($this->richArgs);
    }

    private function trimTrailingNl(string &$string): void
    {
        $string = rtrim($string, "\n");
    }

    private function handleProcessAnonClass()
    {
        if (isset($this->entry['class']) && stringStartsWith(VarDump::ANON_CLASS, $this->entry['class'])) {
            $this->processAnonClass();
        }
    }

    private function processAnonClass()
    {
        $entryFile = stringReplaceFirst(VarDump::ANON_CLASS, '', $this->entry['class']);
        $this->entry['file'] = substr($entryFile, 0, strpos($entryFile, '.php') + 4);
        $this->entry['class'] = VarDump::ANON_CLASS;
        $this->entry['line'] = null;
    }

    private function handleProcessCoreAutoloader()
    {
        if ($this->entry['function'] == 'Chevere\\autoloader') {
            $this->processCoreAutoloader();
        }
    }

    private function processCoreAutoloader()
    {
        $this->entry['file'] = $this->entry['file'] ?? (PATH . 'autoloader.php');
    }

    private function handleProcessNormalizeFile()
    {
        if (isset($this->entry['file']) && false !== strpos($this->entry['file'], '\\')) {
            $this->entry['file'] = pathNormalize($this->entry['file']);
        }
    }
}
