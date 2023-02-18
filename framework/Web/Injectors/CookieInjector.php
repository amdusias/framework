<?php

namespace Framework\Web\Injectors;

use Exception;
use Framework\Base\Injector;
use Framework\Web\Interfaces\ICookie;

/**
 * Class CookieInjector
 */
class CookieInjector extends Injector
{
    /**
     * Строит зависимость
     *
     * @access public
     * @return ICookie
     * @throws Exception
     */
    public function build()
    {
        $session = $this->get('cookie');

        if (!($session instanceof ICookie)) {
            throw new Exception('Компонент `cookie` не зарегистрирован');
        }

        return $session;
    }
}