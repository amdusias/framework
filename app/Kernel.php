<?php

namespace App;

use Framework\Base\Kernel as KernelBase;

/**
 * Class Kernel
 */
class Kernel extends KernelBase
{
    /**
     * Возвращает путь к папке приложения
     *
     * @return string
     */
    public function getAppDir(): string
    {
        return __DIR__;
    }
}