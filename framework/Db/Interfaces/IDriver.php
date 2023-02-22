<?php

namespace Framework\Db\Interfaces;

/**
 * Interface IDriver
 */
interface IDriver
{
    /**
     * Конструктор драйвера
     *
     * @param string $dsn
     * @param array $config
     * @param array $options
     */
    public function __construct(string $dsn, array $config = [], array $options = []);
}