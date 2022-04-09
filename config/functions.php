<?php

/**
 * Debug
 *
 * @param mixed $input
 * @param int $traceOffset
 *
 * @throws \App\Debug\DebugInformationException|\App\Debug\DebuggerException
 */
function debug(mixed $input, int $traceOffset = 3)
{
    App\Debug\Debugger::debug($input, $traceOffset);
}

/**
 * Dump and die
 *
 * @param mixed $input
 * @param int $traceOffset
 *
 * @throws \App\Debug\DebugInformationException|\App\Debug\DebuggerException
 */
function dd(mixed $input, int $traceOffset = 4)
{
    debug($input, $traceOffset);
    die;
}

/**
 * Extract key recursively from a given array
 * E.g. extractKeyRecursively($config, 'Database.host')
 *
 * @param array $array
 * @param string $key
 * @param mixed $default
 *
 * @return mixed
 */
function extractKeyRecursively(array $array, string $key, mixed $default = null): mixed
{
    $result = $array;

    foreach (explode('.', $key) as $recursiveKey) {
        if (isset($result[$recursiveKey])) {
            $result = $result[$recursiveKey];
        } else {
            $result = $default;
            break;
        }
    }

    return $result;
}

/**
 * Returns a string as an underscore version
 *
 * @param string $string
 *
 * @return string
 */
function stringToUnderscore(string $string): string
{
    return mb_strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_' . '\\1', $string));
}

/**
 * Returns a singularized string
 *
 * @param string $string
 *
 * @return string
 */
function singularize(string $string): string
{
    $lastChar = $string[strlen($string) - 1];

    if (mb_strtolower($lastChar) === 's') {
        return substr($string, 0, -1);
    }

    return $string;
}

/**
 * Return if context is CLI
 *
 * @return bool
 */
function isCli(): bool
{
    return PHP_SAPI === 'cli';
}
