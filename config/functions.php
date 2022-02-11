<?php
if (function_exists('debug') === false) {
    /**
     * Debug
     *
     * @param mixed $input
     * @param int   $traceOffset
     *
     * @throws \App\Debug\DebugInformationException
     */
    function debug($input, int $traceOffset = 3)
    {
        App\Debug\Debugger::debug($input, $traceOffset);
    }
}

if (function_exists('dd') === false) {
    /**
     * Dump and die
     *
     * @param mixed $input
     * @param int   $traceOffset
     *
     * @throws \App\Debug\DebugInformationException
     */
    function dd($input, int $traceOffset = 4)
    {
        debug($input, $traceOffset);
        die;
    }
}

if (function_exists('extractKeyRecursively') === false) {
    /**
     * Extract key recursively from a given array
     * E.g. extractKeyRecursively($config, 'Database.host')
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function extractKeyRecursively(array $array, string $key, $default = null)
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
}

if (function_exists('stringToUnderscore') === false) {
    /**
     * Returns a string as an underscore version
     *
     * @param string $string
     *
     * @return string
     */
    function stringToUnderscore(string $string)
    {
        return mb_strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_' . '\\1', $string));
    }
}

if (function_exists('singularize') === false) {
    /**
     * Returns a singularized string
     *
     * @param string $string
     *
     * @return string
     */
    function singularize(string $string): string
    {
        $lastChar = substr($string, -1, 1);

        if (mb_strtolower($lastChar) === 's') {
            return substr($string, 0, -1);
        }

        return $string;
    }
}

if (function_exists('isCli') === false) {
    /**
     * Return if context is CLI
     *
     * @return bool
     */
    function isCli(): bool
    {
        return php_sapi_name() === 'cli';
    }
}