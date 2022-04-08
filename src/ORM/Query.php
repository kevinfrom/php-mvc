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
     * @var array $args
     */
    private array $args = [];

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
     * Add where clause condition
     *
     * @param array|string[] $conditions
     * @return $this
     * @throws BadQueryConditionException
     */
    public function where(array $conditions): Query
    {
        if (array_is_list($conditions)) {
            throw new BadQueryConditionException('Conditions needs to be an associative array to prevent SQL injection.');
        }

        foreach ($conditions as $key => $value) {
            $this->conditions[$key] = $value;
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
        $result = Connection::getInstance()->query($this->formatForQuery(), $this->args, true);

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
    public function formatForQuery(): string
    {
        return
            $this->prepareSelectForQuery()
            . $this->prepareFromForQuery()
            . $this->prepareConditionsForQuery()
            . $this->prepareLimitForQuery()
            . ';';
    }

    /**
     * Prepare SELECT for query
     *
     * @return string
     */
    private function prepareSelectForQuery(): string
    {
        if (empty($this->fields)) {
            return 'SELECT * ';
        }

        $result = ['SELECT'];
        foreach ($this->fields as $field) {
            $result[] = "`$field`";
        }

        return implode(' ', $result);
    }

    /**
     * Prepare FROM for query
     *
     * @return string
     */
    private function prepareFromForQuery(): string
    {
        return " FROM {$this->from}";
    }

    /**
     * Prepare where clause for SQL query
     *
     * @return string
     */
    private function prepareConditionsForQuery(): string
    {
        $result = ' WHERE ';
        $prefix = '';

        foreach ($this->conditions as $key => $value) {
            $result .= "$prefix`$key` = :$key";
            $prefix = ' AND ';
            $this->args[$key] = $value;
        }

        return $result;
    }

    /**
     * Prepare limit for query
     *
     * @return string
     */
    private function prepareLimitForQuery(): string
    {
        return $this->limit ? " LIMIT {$this->limit}" : '';
    }

    /**
     * @return EntityInterface[]
     */
    public function all()
    {
        $result = Connection::getInstance()->query($this->formatForQuery(), $this->args);

        return array_map(function ($data) {
            return $this->model->newEntity($data);
        }, $result);
    }
}
