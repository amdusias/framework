<?php

namespace Framework\Base;

/**
 * Интерфейс для класса зависимостей
 */
interface InjectorInterface
{
    /**
     * Добавляем требование к зависимости
     *
     * @param $name
     * @param $component
     * @return mixed
     */
    public function addRequirement($name, $component);

    /**
     * Строитель зависимости
     *
     * @return mixed
     */
    public function build();
}