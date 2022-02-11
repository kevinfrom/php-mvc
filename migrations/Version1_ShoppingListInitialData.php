<?php

use App\Migrations\Migration;

class Version1_ShoppingListInitialData extends Migration
{

    public function up()
    {
        $this->execute("
            CREATE TABLE `lists` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL DEFAULT '',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function down()
    {
        $this->execute('DROP TABLE `lists`');
    }
}
