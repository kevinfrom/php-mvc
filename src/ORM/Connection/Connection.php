<?php

namespace App\ORM\Connection;

use App\Traits\SingletonTrait;
use PDO;
use PDOException;

/**
 * @method static Connection getInstance(array $_config)
 */
class Connection
{

    use SingletonTrait;

    /**
     * @var PDO|null $_pdo
     */
    private static ?PDO $_pdo;

    /**
     * @var array|bool[]|string[] $_config
     */
    private static array $_config = [
        'host'     => false,
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
     * Execute an SQL query. The result is returned as an array
     *
     * @param string $query
     * @param bool $firstOnly
     *
     * @return array
     */
    public function query(string $query, bool $firstOnly = false): array
    {
        try {
            $result = self::$_pdo->query($query);
            $result->execute();
            $fetchMethod = $firstOnly ? 'fetch' : 'fetchAll';

            return $result->$fetchMethod(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            throw new ConnectionException($e);
        }
    }
}
