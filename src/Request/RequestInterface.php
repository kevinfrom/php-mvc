<?php

namespace App\Request;

/**
 * Interface RequestInterface
 * @package App\Request
 */
interface RequestInterface
{

    /**
     * Get param
     *
     * @param string $key
     * @return string|null
     */
    public function getParam(string $key): ?string;

    /**
     * Get params
     *
     * @return null|string[]
     */
    public function getParams(): ?array;

    /**
     * Get GET query
     *
     * @param string $key
     * @param mixed $default
     * @return string|mixed|null
     */
    public function getQuery(string $key, $default);

    /**
     * Get POST data
     *
     * @param string $key
     * @param mixed $default
     * @return null|string|string[]|string[][]
     */
    public function getData(string $key, $default);
}
