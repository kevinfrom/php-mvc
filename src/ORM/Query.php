<?php

namespace App\ORM;

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
     * @var array $from
     */
    private array $from = [];

    /**
     * To string
     *
     * @return string
     */
    public function __toString(): string
    {
        $where  = $this->conditions === [] ? '' : ' WHERE ' . implode(' AND ', $this->conditions);
        $result = 'SELECT ' . implode(', ', $this->fields);
        $result .= ' FROM ' . implode(', ', $this->from);

        return $result . $where . ';';
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
     * @param string ...$where
     *
     * @return $this
     */
    public function where(string ...$where): Query
    {
        $this->conditions = array_merge($this->conditions, func_get_args());

        return $this;
    }

    /**
     * Add from clause
     *
     * @param string      $table
     * @param string|null $alias
     *
     * @return $this
     */
    public function from(string $table, ?string $alias = null): Query
    {
        $from              = $alias ? "$table as $alias" : $table;
        $this->from[$from] = $from;

        return $this;
    }
}
