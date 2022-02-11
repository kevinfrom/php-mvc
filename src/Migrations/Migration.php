<?php

namespace App\Migrations;

use App\ORM\Connection\Connection;

/**
 * @method static Migration getInstance(Connection $connection)
 */
abstract class Migration implements MigrationInterface
{

    protected Connection $connection;
    private bool $isMigratingUp;

    final public function __construct(Connection $connection, bool $isMigratingUp)
    {
        $this->connection = $connection;
        $this->isMigratingUp = $isMigratingUp;
    }

    final public function execute(string $sql)
    {
        $this->connection->exec($sql);
    }
}
