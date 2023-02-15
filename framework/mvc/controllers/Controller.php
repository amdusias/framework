<?php

namespace Framework\Mvc\Controllers;

use Exception;
use Framework\Mvc\Action;
use Framework\Mvc\Interfaces\IController;
use Framework\Web\ResponseInjector;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Controller
 */
abstract class Controller implements IController
{
    /** @var ResponseInterface $response http данные */
    public ResponseInterface $response;

    /**
     * Конструктор контроллера
     */
    public function __construct()
    {
        if (!$this->response = (new ResponseInjector)->build()) {
            throw new Exception('Компонент `response` не настроен');
        }
    }

    /**
     * Возвращает экшен
     *
     * @param $name
     * @return false|Action|string
     */
    public function getActionClassByName($name)
    {
        if (method_exists($this, 'actions')) {
            $actions = $this->actions();

            if (
                !empty($actions[$name]) &&
                class_exists($actions[$name]) &&
                is_subclass_of($actions[$name], '\Framework\Mvc\Action')
            ) {
                return $actions[$name];
            }
        }

        return false;
    }
}