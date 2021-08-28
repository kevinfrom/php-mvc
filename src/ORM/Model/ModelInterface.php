<?php

namespace App\ORM\Model;

use App\ORM\Entity\EntityInterface;
use App\ORM\Query;

/**
 * Class ModelInterface
 */
interface ModelInterface
{

    /**
     * Returns a new query object
     *
     * @return Query
     */
    public function query(): Query;

    /**
     * New entity
     *
     * @param array $data
     *
     * @return EntityInterface
     */
    public function newEntity(array $data = []): EntityInterface;
}
