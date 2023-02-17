<?php

namespace Framework\Web\Injectors;

use Exception;
use Framework\Base\Injector;
use Framework\Web\Interfaces\ISession;

/**
 * Class SessionInjector
 */
class SessionInjector extends Injector
{
    /**
     * Строит зависимость
     *
     * @access public
     * @return ISession
     * @throws Exception
     */
    public function build()
    {
        $session = $this->get('session');

        if (!($session instanceof ISession)) {
            throw new Exception('Компонент `session` не зарегистрирован');
        }

        return $session;
    }
}