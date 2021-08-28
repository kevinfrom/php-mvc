<?php

namespace App\ORM\Entity;

/**
 * Interface EntityInterface
 *
 * @package App\ORM\Entity
 */
interface EntityInterface
{

    /**
     * Get a value by key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Set a value by key
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return EntityInterface
     */
    public function set(string $key, mixed $value): EntityInterface;
}
