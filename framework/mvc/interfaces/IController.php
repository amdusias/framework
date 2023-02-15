<?php

namespace Framework\Mvc\Interfaces;

/**
 * Class IController
 */
interface IController
{
    /**
     * Экшен
     *
     * @param string $name
     */
    public function action(string $name = 'index');
}