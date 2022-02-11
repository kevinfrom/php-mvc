<?php

namespace App\Console;

use App\Traits\SingletonTrait;
use ReflectionClass;
use ReflectionMethod;

/**
 * @method static Shell getInstance(Console $console)
 */
abstract class Shell implements ShellInterface
{

    use SingletonTrait;

    private Console $console;

    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    /**
     * @throws ConsoleException
     */
    public function help()
    {
        $reflectionClass = new ReflectionClass($this);

        $methods = array_map(
            fn($method) => $method->getName(),
            $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC)
        );
        $methods = array_filter($methods, function ($method) {
            return in_array($method, ['__construct', 'getInstance']) === false;
        });

        $this->console->output(['Available methods are:', ...$methods]);
    }
}
