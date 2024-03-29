<?php

namespace Framework\Base\Injectors;

use Exception;
use Framework\Base\Injector;
use Framework\Base\Interfaces\IKernel;

/**
 * Class KernelInjector
 */
class KernelInjector extends Injector
{
    /**
     * Строит зависимость
     *
     * @return IKernel|null
     * @throws \ReflectionException
     */
    public function build()
    {
        $kernel = $this->get('kernel');

        if (!($kernel instanceof IKernel)) {
            throw new Exception('Компонент `kernel` не зарегистрирован');
        }

        return $kernel;
    }
}