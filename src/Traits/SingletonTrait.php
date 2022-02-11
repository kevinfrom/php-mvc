<?php

namespace App\Traits;

trait SingletonTrait
{
    private static $instance;

    private function __construct($args)
    {
    }

    public static function getInstance($args = null)
    {
        if (empty(self::$instance)) {
            self::$instance = new static($args);
        }

        return self::$instance;
    }
}