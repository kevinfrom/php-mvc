<?php

namespace App\ORM\Connection;

use PDO;

/**
 * Class Connection
 *
 * @package App\ORM\Connection
 */
class Connection
{

    /**
     * @var PDO|null $_pdo
     */
    private ?PDO $_pdo;

    /**
     * @var array|false[] $_config
     */
    private array $_config = [
        'host' => false,
        'username' => false,
        'password' => false,
        'database' => false,
    ];

    /**
     * Connection constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->_setConfig($config);
        $this->_connect();
    }

    /**
     * Set config
     *
     * @param array $config
     */
    private function _setConfig(array $config)
    {
        $this->_config = array_merge($this->_config, $config);
    }

    /**
     * Create a PDO object and establish a connection
     */
    private function _connect()
    {
        $dsn = '';
        $this->_pdo = new PDO($dsn);
        $this->_pdo->query('USE ' . $this->_config['database'] . ';');
    }
}
