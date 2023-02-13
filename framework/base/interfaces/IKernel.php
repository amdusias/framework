<?php

namespace Framework\Base\Interfaces;

/**
 * Interface IKernel
 */
interface IKernel
{
    /**
     * Возвращает путь к папке приложения
     *
     * @return mixed
     */
    public function getAppDir(): mixed;

    /**
     * Возвращает путь к папке настроек приложения
     *
     * @return string
     */
    public function getConfigDir(): string;
}