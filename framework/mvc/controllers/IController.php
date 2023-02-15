<?php

namespace Framework\Mvc\Controllers;

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