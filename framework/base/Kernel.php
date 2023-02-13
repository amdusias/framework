<?php

namespace Framework\Base;

use Framework\Base\Interfaces\IKernel;

/**
 * Class Kernel
 */
class Kernel implements IKernel
{
    /** минимальная версия php */
    const PHP_MIN = '8.0.0';

    /** @var string $appDir путь к папке приложения */
    protected $appDir;

    /**
     * Возвращает путь к папке приложения
     *
     * @return mixed
     */
    public function getAppDir(): mixed
    {
        if (!$this->appDir) {
            $this->appDir = realpath(dirname((new \ReflectionObject($this))->getFileName()));
        }

        return $this->appDir;
    }

    /**
     * Возвращает путь к папке настроек приложения
     *
     * @return string
     */
    public function getConfigDir(): string
    {
        return $this->getAppDir() . '/configs/index.php';
    }
}