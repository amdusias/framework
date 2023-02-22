<?php

namespace Framework\Db\Drivers;

use Exception;
use Framework\Db\Interfaces\IAdapter;
use Framework\Db\Interfaces\IDriver;

/**
 * Class Connection
 */
class Connection implements IAdapter
{
    /** @var IDriver $driver */
    private IDriver $driver;

    /**
     * Конструктор
     *
     * @param string $dsn
     * @param array $config
     * @param array $options
     * @throws Exception
     */
    public function __construct(string $dsn, array $config = [], array $options = [])
    {
        $this->setDriver($dsn, $config, $options);
    }

    /**
     * Возвращает драйвер
     *
     * @return IDriver
     */
    public function getDriver(): IDriver
    {
        return $this->driver;
    }

    /**
     * Сохраняет драйвер
     *
     * @param $dsn
     * @param array $config
     * @param array $options
     * @return void
     * @throws Exception
     */
    public function setDriver($dsn, array $config = [], array $options = []): void
    {
        $class = '\Framework\Db\Drivers\\'.ucfirst(substr($dsn, 0, strpos($dsn, ':'))).'Driver';

        if (!class_exists($class)) {
            throw new Exception('Драйвер `'.$class.'` не существует');
        }

        unset($this->driver);

        $this->driver = new $class($dsn, $config, $options);
    }
}