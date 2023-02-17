<?php

namespace Framework\Web\Injectors;

use Exception;
use Framework\Base\Injector;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RequestInjector
 */
class RequestInjector extends Injector
{
    /**
     * Строит зависимость
     *
     * @access public
     * @return ServerRequestInterface
     * @throws Exception
     */
    public function build()
    {
        $request = $this->get('request');

        if (!($request instanceof ServerRequestInterface)) {
            throw new Exception('Компонент `request` не зарегистрирован');
        }

        return $request;
    }
}