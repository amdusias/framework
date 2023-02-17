<?php

namespace Framework\Web;

use Exception;
use Framework\Base\Injector;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseInjector
 */
class ResponseInjector extends Injector
{
    /**
     * Создает зависимость
     *
     * @access public
     * @return ResponseInterface
     * @throws Exception
     */
    public function build()
    {
        $response = $this->get('response');

        if (!($response instanceof ResponseInterface)) {
            throw new Exception('Компонент `response` не зарегистрирован');
        }

        return $response;
    }
}