<?php

namespace App\ORM\Model;

use App\ORM\Entity\EntityInterface;
use App\ORM\Entity\MissingEntityException;
use App\ORM\Query;

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
     * @var string $entity
     */
    private string $entityClass;

    /**
     * ListsModel constructor
     */
    public function __construct()
    {
        $table       = explode('\\', self::class);
        $table       = end($table);
        $this->table = str_replace('_model', '', stringToUnderscore($table));

        $this->entityClass = singularize(ucfirst(mb_strtolower($this->table)));
        $this->entityClass = '\\App\\ORM\\Entity\\' . $this->entityClass . 'Entity';
    }

    /**
     * @inheritDoc
     */
    public function query(): Query
    {
        return new Query($this);
    }

    /**
     * @inheritDoc
     */
    public function newEntity(array $data = []): EntityInterface
    {
        if (class_exists($this->entityClass) === false) {
            throw new MissingEntityException("Entity {$this->entityClass} does not exist");
        }

        return new $this->entityClass($data);
    }
}
