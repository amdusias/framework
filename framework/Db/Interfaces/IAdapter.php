<?php

namespace Framework\Db\Interfaces;

/**
 * Interface IDb
 */
interface IAdapter
{
    /**
     * Сохраняет драйвер
     *
     * @param string $dsn
     * @param array $config
     * @param array $options
     * @return void
     */
    public function setDriver(string $dsn, array $config = [], array $options = []): void;

    /**
     * Возвращает драйвер
     *
     * @return IDriver
     */
    public function getDriver(): IDriver;
}