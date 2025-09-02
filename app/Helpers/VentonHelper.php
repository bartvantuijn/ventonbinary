<?php

namespace App\Helpers;

use Illuminate\Console\Command;
use Illuminate\Process\ProcessResult;

class VentonHelper
{
    public static function getHomePath(): string
    {
        // todo: set up for other families
        if (PHP_OS_FAMILY === 'Darwin') {
            return $_SERVER['HOME'];
        } else {
            return '';
        }
    }

    public static function getLibraryPath(): string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return $_SERVER['APPDATA'] . '/Venton';
        } elseif (PHP_OS_FAMILY === 'Darwin') {
            return $_SERVER['HOME'] . '/Library/Venton';
        } else {
            return $_SERVER['HOME'] . '/.config/venton';
        }
    }

    public static function getExitDetails(Command $command, ProcessResult $result): void
    {
        $command->error('Command failed with exit code ' . $result->exitCode());
        $command->line($result->errorOutput());
    }
}
