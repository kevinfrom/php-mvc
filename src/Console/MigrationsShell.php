<?php

namespace App\Console;

use App\Migrations\Migration;
use App\Migrations\MigrationException;
use App\ORM\Connection\Connection;
use PDOException;

class MigrationsShell extends Shell
{

    private bool $isMigratingUp = true;
    private Connection $connection;
    private array $completedMigrations = [];
    private array $pendingMigrations = [];
    private array $migrationsToRun = [];

    public function initialize()
    {
        $this->connection = Connection::getInstance();

        if ($this->migrationsTableExists() === false) {
            $this->createMigrationsTable();
        }

        $this->populateMigrations();
    }

    private function migrationsTableExists(): bool
    {
        try {
            $result = $this->connection->query('SELECT 1 FROM migrations;', true);

            return $result !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    private function createMigrationsTable()
    {
        $this->connection->exec(
            'CREATE TABLE `migrations` (
                    `file` VARCHAR(255) NOT NULL,
                    `completed` TINYINT(1) NOT NULL,
                    `completed_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`file`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
        );
    }

    private function populateMigrations()
    {
        $migrations = $this->connection->query('
            SELECT `file`
            FROM `migrations`
            WHERE `completed` = 1
            ORDER BY `completed_date` ASC;
        ');
        $this->completedMigrations = array_map(fn($row) => $row['file'], $migrations);

        $migrationFiles = glob(MIGRATIONS . DS . 'Version[1]_*.php');
        $this->pendingMigrations = array_filter($migrationFiles, function ($migrationFile) {
            return in_array($migrationFile, $this->completedMigrations) === false;
        });

        if ($this->isMigratingUp) {
            $this->migrationsToRun = $this->pendingMigrations;
        } else {
            $this->migrationsToRun = array_reverse($this->completedMigrations);
        }

        $this->migrationsToRun = array_map(function ($migrationFile) {
            require_once $migrationFile;
            return str_replace(MIGRATIONS . DS, null, $migrationFile);
        }, $this->migrationsToRun);
    }

    /**
     * @return void
     * @throws MigrationException
     */
    public function migrate()
    {
        $this->isMigratingUp = true;
        $this->executeMigrationsToRun();
    }

    /**
     * @return void
     * @throws MigrationException
     */
    private function executeMigrationsToRun()
    {
        $this->output([
            'Starting migrations...',
            'Migration ' . ($this->isMigratingUp ? 'up' : 'down'),
        ]);

        array_map(function ($migration) {
            $this->output([
                "Starting migration: $migration",
                call_user_func([$this->getMigrationInstance($migration), $this->isMigratingUp ? 'up' : 'down']),
                "Finished migration: $migration",
            ]);

            $this->markAsMigrated($migration);
        }, $this->migrationsToRun);

        $this->output('Finished migrating ' . ($this->isMigratingUp ? 'up' : 'down') . '!');
    }

    /**
     * @param string|string[]|array $message
     * @return void
     */
    private function output($message)
    {
        echo str_repeat('-', 80);
        echo PHP_EOL;

        foreach (array_filter((array)$message) as $string) {
            echo "[Migrations] $string\n";
        }

        echo str_repeat('-', 80);
        echo str_repeat(PHP_EOL, 2);
    }

    /**
     * @param string $migrationFile
     * @return Migration
     * @throws MigrationException
     */
    private function getMigrationInstance(string $migrationFile): Migration
    {
        $className = substr($migrationFile, 0, -4);
        $migration = new $className($this->connection, $this->isMigratingUp);

        if ($migration instanceof Migration === false) {
            throw new MigrationException("Migration $migrationFile does not extend Migration");
        }

        return $migration;
    }

    private function markAsMigrated(string $migration)
    {
        if ($this->isMigratingUp) {
            $query = $this->connection->prepare('INSERT INTO migrations VALUES(:file, :completed, :completed_date)');
            $query->execute([
                ':file'           => $migration,
                ':completed'      => 1,
                ':completed_date' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $query = $this->connection->prepare('DELETE FROM `migrations` WHERE `file` = :file;');
            $query->execute([':file' => $migration]);
        }
    }

    /**
     * @return void
     * @throws MigrationException
     */
    public function rollback()
    {
        $this->isMigratingUp = false;
        $this->executeMigrationsToRun();
    }
}
