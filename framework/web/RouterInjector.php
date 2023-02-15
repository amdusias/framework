<?php

namespace Framework\Web;

use Exception;
use Framework\Base\Injector;
use Framework\Web\Interfaces\IRouter;

/**
 * Class RouterInjector
 */
class RouterInjector extends Injector
{
    /**
     * Строит зависимость
     *
     * @return IRouter
     * @throws Exception
     */
    public function build()
    {
        $router = $this->get('router');

        if (!($router instanceof IRouter)) {
            throw new Exception('Компонент `router` не зарегистрирован');
        }

        return $router;
    }
}