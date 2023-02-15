<?php

namespace Framework\Mvc\Interfaces;

/**
 * Interface IResolver
 */
interface IResolver
{
    /**
     * Возвращает экземпляр класса по запросу
     */
    public function getApp();

    /**
     * Возвращает экшен по запросу
     */
    public function getAction();
}