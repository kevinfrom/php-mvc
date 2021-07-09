<?php
if (function_exists('debug') === false) {
    /**
     * Debug
     *
     * @param mixed $input
     */
    function debug($input)
    {
        var_dump($input);
    }
}

if (function_exists('dd') === false) {
    /**
     * Dump and die
     *
     * @param mixed $input
     */
    function dd($input)
    {
        debug($input);
        die;
    }
}

if (function_exists('extractKeyRecursively') === false) {
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
