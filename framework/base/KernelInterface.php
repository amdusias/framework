<?php

namespace Framework\Base;

/**
 * Интерфейс для класса ядра
 */
interface KernelInterface
{
    public function getAppDir();

    public function getWebDir();

    public function getConfig();

    public function getEnvironment();

    public function getStartTime();

    public function isCli();

    public function isDebug();

    public function getLogDir();

    public function getCacheDir();
}