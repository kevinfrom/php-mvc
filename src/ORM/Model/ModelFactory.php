<?php

namespace App\ORM\Model;

/**
 * Class ModelFactory
 *
 * @package App\ORM\Model
 */
class ModelFactory
{

    /**
     * Get model
     *
     * @param string $model
     * @return null|ModelInterface
     */
    public static function getModel(string $model): ?ModelInterface
    {
        $class = 'App\\ORM\\Model\\' . ucfirst(mb_strtolower($model)) . 'Model';
        if (class_exists($class)) {
            return new $class;
        }

        return null;
    }
}
