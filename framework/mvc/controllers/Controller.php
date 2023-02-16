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
            throw new Exception('Компонент `response` не зарегистрирован');
        }
    }

    /**
     * Применяет фильтры
     *
     * @param $action
     * @param bool $isPre
     * @param array $filters
     * @param $data
     * @return mixed|ResponseInterface|null
     * @throws Exception
     */
    public function applyFilters($action, $isPre = true, array $filters = [], $data = null)
    {
        if (!$filters) {
            return $data;
        }

        foreach ($filters as $filter) {
            if (empty($filter['class']) || !class_exists($filter['class'])) {
                continue;
            }

            if (empty($filter['actions']) || !in_array($action, $filter['actions'], true)) {
                continue;
            }

            $_filter = new $filter['class']($action);
            $response = $isPre ? $_filter->pre($filter) : $_filter->post($filter + ['data' => $data]);

            if (!$response) {
                if (!empty($_filter->result['redirect'])) {
                    $redirect = (new ResponseInjector)->build();
                    return $redirect->withHeader('Location', $_filter->result['redirect']);
                }
                throw new Exception($_filter->result['message']);
            }

            $data = $response;
        }

        return $data;
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