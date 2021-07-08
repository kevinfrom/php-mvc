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
