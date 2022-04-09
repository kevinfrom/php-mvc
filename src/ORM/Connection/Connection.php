<?php

namespace App\ORM\Connection;

use App\Traits\SingletonTrait;
use PDO;
use PDOException;
use PDOStatement;

/**
 * @method static Connection getInstance
 */
class Connection
{

    use SingletonTrait;

    /**
     * @var PDO|null $_pdo
     */
    public ?PDO $_pdo;

    /**
     * @var array|bool[]|string[] $_config
     */
    private array $_config = [
        'host'     => false,
        'username' => false,
        'password' => false,
        'database' => false,
    ];

    public function initialize(array $_config)
    {
        $this->_setConfig($_config);
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
        try {
            extract($this->_config, EXTR_OVERWRITE);
            $this->_pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_CURSOR             => PDO::CURSOR_FWDONLY,
            ]);
            $this->_pdo->exec('SET NAMES utf8;');
        } catch (PDOException $e) {
            throw new ConnectionException($e);
        }
    }

    public function prepare(string $query): PDOStatement
    {
        return $this->_pdo->prepare($query);
    }

    /**
     * Execute an SQL query. The result is returned as an array
     *
     * @param string $query
     * @param array $params
     * @param bool $firstOnly
     *
     * @return array
     */
    public function query(string $query, array $params, bool $firstOnly = false): array
    {
        try {
            $queryObj = $this->_pdo->prepare($query);
            $queryObj->execute($params);

            return $queryObj->{$firstOnly ? 'fetch' : 'fetchAll'}() ?: [];
        } catch (PDOException $e) {
            throw new ConnectionException($e);
        }
    }

    /**
     * Execute a query directly
     *
     * @param string $sql
     * @return false|int
     */
    public function exec(string $sql)
    {
        try {
            return $this->_pdo->exec($sql);
        } catch (PDOException $e) {
            throw new ConnectionException($e);
        }
    }
}
