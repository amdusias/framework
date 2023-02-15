<?php

namespace Framework\Base\Interfaces;

/**
 * Interface IDispatcher
 */
interface IDispatcher
{
    /**
     * Добавляет событие
     *
     * @param $listener
     * @param $event
     * @param $prior
     * @return mixed
     */
    public function addListener($listener, $event, $prior = null);

    /**
     * Отправляет сигнал для старта события
     *
     * @param $listener
     * @param array $params
     * @return mixed
     */
    public function signal($listener, array $params = []);
}