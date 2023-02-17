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
     * Возвращает путь к файлу настроек приложения
     *
     * @return string
     */
    public function getConfigFile(): string;

    /**
     * Возвращает путь к клиентской папке
     *
     * @return string
     */
    public function getWebDir(): string;

    /**
     * Возвращает статус дебаг режима
     *
     * @return bool
     */
    public function isDebug(): bool;

    /**
     * Возвращает путь к папке ресурсов
     *
     * @return string
     */
    public function getResourcesDir(): string;
}