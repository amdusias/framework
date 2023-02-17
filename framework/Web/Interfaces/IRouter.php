<?php

namespace Framework\Web\Interfaces;

/**
 * Interface IRouter
 */
interface IRouter
{
    /**
     * Парсит url адрес
     *
     * @param string $uri
     * @param string $method
     */
    public function parse(string $uri, string $method = 'GET');
}