<?php

namespace Framework\Mvc\Controllers;

use Exception;
use Framework\Web\ResponseInjector;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ViewController
 */
abstract class ViewController extends Controller
{
    /** @var string $layout шаблон */
    public string $layout;

    /**
     * Экшен
     *
     * @param string $name
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(string $name = 'index')
    {
        $actionClass = false;

        if (!method_exists($this, 'action'.ucfirst($name))) {
            $actionClass = $this->getActionClassByName($name);

            if (!$actionClass) {
                throw new Exception('Экшен `'.$name.'` не найден в контроллере '.get_class($this));
            }
        }

        $filters = method_exists($this, 'filters') ? $this->filters() : [];
        $result = $this->applyFilters($name, true, $filters, null);

        if ($result instanceof ResponseInterface) {
            return $result;
        }

        if ($actionClass) {
            $cl = new $actionClass();
            $view = $cl->run();
        } else {
            $view = $this->{'action'.ucfirst($name)}();
        }

        if (is_object($view)) {
            $view->layout = $view->layout ?: $this->layout;
            $view->view = $view->view ?: $name;
            $view->path = get_called_class();
            $view = $view->render();
        }

        $response = (new ResponseInjector)->build();
        if (!$response) {
            throw new Exception('Компонент `response` не зарегистрирован');
        }

        $stream = $response->getBody();
        $stream->write($this->applyFilters($name, false, $filters, $view));

        return $response->withBody($stream);
    }
}