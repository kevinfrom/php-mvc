<?php

namespace App\ORM\Connection;

use PDOException;
use Throwable;

/**
 * Class ConnectionException
 */
class ConnectionException extends PDOException
{

    /**
     * @param PDOException $exception
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(PDOException $exception, $code = 0, Throwable $previous = null)
    {
        parent::__construct($exception->getMessage(), $code, $previous);
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        $result = get_class($this) . ': ';

        $message = $this->getMessage();

        // Anonymize connection data
        preg_match_all('/\'([^\']+)\'/', $message, $matches);
        if (isset($matches[1])) {
            foreach ($matches[1] as $match) {
                $message = str_replace($match, str_repeat('*', strlen($match)), $message);
            }
        }
        $result .= $message;

        $result .= ' in ' . $this->getFile();
        $result .= ' Stack trace: ' . $this->getTraceAsString();

        return $result;
    }
}
