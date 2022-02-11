<?php

namespace App\Migrations;

interface MigrationInterface
{

    public function execute(string $sql);

    public function up();

    public function down();
}
