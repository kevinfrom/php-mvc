<?php

namespace App\ORM\Connection;

use App\Traits\SingletonTrait;
use PDO;
use PDOException;

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
            extract($this->_config);
            $this->_pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $this->_pdo->exec('SET NAMES utf8');
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
            $result = $this->_pdo->query($query);
            $fetchMethod = $firstOnly ? 'fetch' : 'fetchAll';

            return $result->$fetchMethod(PDO::FETCH_ASSOC) ?: [];
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
