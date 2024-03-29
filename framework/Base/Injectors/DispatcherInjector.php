<?php

namespace Framework\Base\Injectors;

use Exception;
use Framework\Base\Injector;
use Framework\Base\Interfaces\IDispatcher;

/**
 * Class DispatcherInjector
 */
class DispatcherInjector extends Injector
{
    /**
     * Создает зависимость
     *
     * @return IDispatcher|null
     * @throws \ReflectionException
     */
    public function build()
    {
        $dispatcher = $this->get('dispatcher');

        if (!($dispatcher instanceof IDispatcher)) {
            throw new Exception('Компонент `dispatcher` не зарегистрирован');
        }

        return $dispatcher;
    }
}