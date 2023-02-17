<?php

namespace Framework\Base\Interfaces;

/**
 * Interface IInjector
 */
interface IInjector
{
    /**
     * Добавляет зависимость или настройку
     *
     * @param string $name
     * @param mixed $component
     * @return void
     */
    public function addRequirement($name, $component): void;

    /**
     * Строит зависимость
     *
     * @return null
     */
    public function build();
}