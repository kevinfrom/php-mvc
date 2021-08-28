<?php

namespace App\ORM\Entity;

/**
 * Class Entity
 *
 * @package App\ORM\Entity
 */
class Entity implements EntityInterface
{

    /**
     * Entity Constructor
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        return $this->{$key} ?? null;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value): EntityInterface
    {
        $this->{$key} = $value;

        return $this;
    }
}
