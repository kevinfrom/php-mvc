<?php

namespace App\ORM;

use App\ORM\Connection\Connection;
use App\ORM\Entity\EntityInterface;
use App\ORM\Model\ModelInterface;

class Query
{

    /**
     * @var array $fields
     */
    private array $fields = [];

    /**
     * @var array $conditions
     */
    private array $conditions = [];

    /**
     * @var string $from
     */
    private string $from;

    /**
     * @var int $limits
     */
    private int $limit = 0;

    /**
     * @var ModelInterface $model
     */
    private ModelInterface $model;

    /**
     * Query Constructor
     *
     * @param ModelInterface $model
     */
    public function __construct(ModelInterface $model)
    {
        $this->model = $model;
        $this->from = $model->getTable();
    }

    /**
     * Add select clause
     *
     * @param string ...$select
     *
     * @return $this
     */
    public function select(string ...$select): Query
    {
        foreach (func_get_args() as $select) {
            $this->fields[$select] = $select;
        }

        return $this;
    }

    /**
     * Add where clause
     *
     * @param array|string[]|string $conditions
     *
     * @return $this
     */
    public function where($conditions): Query
    {
        if (is_string($conditions)) {
            $this->conditions[] = $conditions;
        } else {
            foreach ($conditions as $key => $value) {
                if (ctype_digit($key)) {
                    $this->conditions[] = $value;
                } else {
                    $this->conditions[$key] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Return first result
     *
     * @return EntityInterface|null
     */
    public function first()
    {
        $this->limit(1);
        $result = Connection::getInstance()->query($this->__toString(), true);

        if ($result) {
            $result = $this->model->newEntity($result);
        }

        return $result;
    }

    /**
     * Set limit
     *
     * @param int $limit
     *
     * @return Query
     */
    public function limit(int $limit): Query
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString(): string
    {
        $where = $this->conditions === [] ? '' : ' WHERE ' . implode(' AND ', $this->conditions);

        if ($this->fields) {
            $result = 'SELECT ' . implode(', ', $this->fields);
        } else {
            $result = 'SELECT * ';
        }

        $result .= 'FROM ' . $this->from;
        $result .= $this->prepareWhereForQuery();

        if ($this->limit) {
            $result .= ' LIMIT ' . $this->limit;
        }

        return $result . ';';
    }

    /**
     * Prepare where clause for SQL query
     *
     * @return string
     */
    private function prepareWhereForQuery(): string
    {
        $result = '';

        foreach ($this->conditions as $key => $value) {
            if (ctype_digit($key)) {
                $result .= " $value";
            } else {
                $result .= " $key = `$value`";
            }
        }
        dd(' ' . trim($result));

        return ' ' . trim($result);
    }

    /**
     * @return EntityInterface[]
     */
    public function all()
    {
        $result = Connection::getInstance()->query($this->__toString());

        return array_map(function ($data) {
            return $this->model->newEntity($data);
        }, $result);
    }
}
