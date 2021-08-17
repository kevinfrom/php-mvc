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
    private static ?PDO $_pdo;

    /**
     * @var Connection|null $_instance
     */
    private static ?Connection $_instance;

    /**
     * @var array|bool[]|string[] $_config
     */
    private static array $_config = [
        'host' => false,
        'username' => false,
        'password' => false,
        'database' => false,
    ];

    /**
     * Connection constructor.
     *
     * @param array $_config
     */
    private function __construct(array $_config)
    {
        self::_setConfig($_config);
        self::_connect();
    }

    /**
     * Initialize
     *
     * @param array $config
     */
    public static function initialize(array $config)
    {
        self::$_instance = new self($config);
    }

    /**
     * Set config
     *
     * @param array $config
     */
    private static function _setConfig(array $config)
    {
        self::$_config = array_merge(self::$_config, $config);
    }

    /**
     * Create a PDO object and establish a connection
     */
    private static function _connect()
    {
        try {
            $dsn = 'mysql:host=' . self::$_config['host'] . ';';
            $dsn .= 'dbname=' . self::$_config['database'] . ';';
            self::$_pdo = new PDO($dsn, self::$_config['username'], self::$_config['password']);
        } catch (\Throwable $e) {
            /**
             * @var \PDOException $e
             */
            throw new ConnectionException($e);
        }
    }

    /**
     * Get connection instance
     *
     * @return Connection
     */
    public static function getInstance(): Connection
    {
        return self::$_instance;
    }
}
