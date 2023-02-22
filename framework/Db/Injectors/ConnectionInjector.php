<?php

namespace Framework\Db\Injectors;

use Exception;
use Framework\Base\Injector;
use Framework\Db\Interfaces\IAdapter;
use Framework\Db\Interfaces\IDriver;

/**
 * Class DbInjector
 */
class ConnectionInjector extends Injector
{
    /**
     * Строит зависимость
     *
     * @return IAdapter
     * @throws \ReflectionException
     */
    public function build()
    {
        $connection = $this->get('connection');

        if (!($connection instanceof IAdapter)) {
            throw new Exception('Компонент `connection` не зарегистрирован');
        }

        return $connection;
    }

    /**
     * Возвращает драйвер
     *
     * @return IDriver
     * @throws \ReflectionException
     */
    public function getDriver(): IDriver
    {
        return $this->build()->getDriver();
    }
}