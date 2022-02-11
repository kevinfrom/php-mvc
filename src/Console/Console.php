<?php

namespace App\Console;

use App\Traits\SingletonTrait;

/**
 * @method static Console getInstance(array $params)
 */
class Console
{

    use SingletonTrait;

    private ShellInterface $shell;
    private ?string $method;
    private ?array $args;

    /**
     * Connection constructor.
     *
     * @param array $params
     * @throws ConsoleException
     */
    private function __construct(array $params)
    {
        array_shift($params);
        $this->shell = $this->getShellInstance(array_shift($params));
        $this->method = array_shift($params);
        $this->args = $params;
    }

    /**
     * @throws ConsoleException
     */
    private function getShellInstance(string $shellClass): Shell
    {
        $shell = 'App\Console\\' . ucfirst($shellClass) . 'Shell';

        if (class_exists($shell) === false) {
            throw new ConsoleException("Shell $shellClass does not exist");
        }

        return new $shell($this);
    }

    /**
     * @throws ConsoleException
     */
    public function output($message)
    {
        if (is_array($message)) {
            $message = implode(PHP_EOL, $message);
        } elseif (is_string($message) === false) {
            throw new ConsoleException('Message is not a string or an array of strings');
        }

        echo str_repeat('-', 80);
        echo PHP_EOL;
        echo $message;
        echo PHP_EOL;
        echo str_repeat('-', 80);
        echo PHP_EOL;
    }

    /**
     * @throws ConsoleException
     */
    public function run()
    {
        if ($this->method === null) {
            $this->shell->help();
        } else {
            $this->shell->initialize();
            call_user_func([$this->shell, $this->method ?: 'help'], $this->args);
        }
    }
}
