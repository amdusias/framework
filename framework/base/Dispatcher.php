<?php

namespace Framework\Base;

use Framework\Base\Interfaces\IDispatcher;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Dispatcher
 */
class Dispatcher implements IDispatcher
{
    /** @var array $listeners слушатели событий */
    protected $listeners = [];

    /**
     * Добавляем событие
     *
     * @param $listener
     * @param $event
     * @param $prior
     * @return bool
     */
    public function addListener($listener, $event, $prior = null)
    {
        if (!is_callable($event)) {
            return false;
        }

        if (!array_key_exists($listener, $this->listeners)) {
            $this->listeners[$listener] = [];
        }

        if (!$prior) {
            $this->listeners[$listener][] = $event;
        } else {
            array_splice($this->listeners[$listener], $prior, 0, $event);
        }

        return true;
    }

    /**
     * Отправляем сигнал для старта события
     *
     * @param $listener
     * @param array $params
     * @return mixed|ResponseInterface|null
     */
    public function signal($listener, array $params = [])
    {
        $result = null;

        if (array_key_exists($listener, $this->listeners) && 0 !== count($this->listeners[$listener])) {
            foreach ($this->listeners[$listener] as $listen) {
                $result = call_user_func($listen, $params);

                if ($result instanceof ResponseInterface) {
                    return $result;
                }
            }
        }

        return $result;
    }
}