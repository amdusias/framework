<?php

namespace Framework\Mvc;

/**
 * Class Action
 */
abstract class Action
{
    /**
     * @result void
     */
    public function __construct() {}

    /**
     * Запускает экшен
     *
     * @return mixed
     */
    abstract public function run();
}