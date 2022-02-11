<?php

namespace App\Traits;

trait SingletonTrait
{
    private static $instance;

    private function __construct($args)
    {
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new static(...func_get_args());
        }

        return self::$instance;
    }
}