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
     * @return string
     */
    public function getAppDir(): string;

    /**
     * Возвращает путь к папке настроек приложения
     *
     * @return string
     */
    public function getConfigDir(): string;

    /**
     * Возвращает путь к клиентской папке
     *
     * @return string
     */
    public function getPublicDir(): string;

    /**
     * Возвращает статус дебаг режима
     *
     * @return bool
     */
    public function isDebug(): bool;
}