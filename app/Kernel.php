<?php

namespace App;

use Framework\Base\Kernel as KernelBase;

/**
 * Класс ядра
 */
class Kernel extends KernelBase
{
    /**
     * Возвращаем путь к приложению
     *
     * @return string
     */
    public function getAppDir(): string
    {
        return __DIR__;
    }
}