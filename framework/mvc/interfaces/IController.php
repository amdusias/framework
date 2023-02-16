<?php

namespace Framework\Mvc\Interfaces;

/**
 * Class IController
 */
interface IController
{
    /**
     * Применяет фильтры
     *
     * @param $action
     * @param $isPre
     * @param array $filters
     * @param $data
     * @return mixed
     */
    public function applyFilters($action, $isPre = true, array $filters = [], $data = null);

    /**
     * Экшен
     *
     * @param string $name
     */
    public function action(string $name = 'index');
}