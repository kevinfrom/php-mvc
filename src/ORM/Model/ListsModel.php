<?php

namespace App\ORM\Model;

/**
 * Class ListsModel
 *
 * @package App\ORM\Model
 */
class ListsModel implements ModelInterface
{

    /**
     * @var string $table
     */
    private string $table;

    /**
     * ListsModel constructor
     */
    public function __construct()
    {
        $table = explode('\\', self::class);
        $table = end($table);
        $this->table = str_replace('_model', '', stringToUnderscore($table));
    }

}
