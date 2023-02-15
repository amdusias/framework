<?php

namespace Framework\Mvc;

use Exception;
use Framework\Base\KernelInjector;
use Framework\Mvc\Interfaces\IResolver;
use Framework\Web\RequestInjector;
use Framework\Web\RouterInjector;

/**
 * Class MvcResolver
 */
class MvcResolver implements IResolver
{
    /** @var string $uri создание url адреса */
    protected string $uri;
    /** @var string $controller запуск интерфеса контроллера */
    private string $controller;
    /** @var string $action запуск экшенов */
    private $action;

    /**
     * Возвращает экземпляр класса по запросу
     *
     * @throws \Exception
     */
    public function getApp()
    {
        $request = (new RequestInjector)->build();
        $rawQuery = $request->getQueryParams();

        $query = !empty($rawQuery['r']) ? $rawQuery['r'] : '/default';
        $query = (substr($query, -1) === '/') ? substr($query, 0, -1) : $query;

        $this->uri = (new RouterInjector)->build()->parse($query, $request->getMethod());

        $this->initialize();

        $cls = $this->getCalculatePath();

        if (!class_exists($cls) || !is_subclass_of($cls, '\Framework\Mvc\Controllers\Interfaces\IController')) {
            throw new Exception('Контроллер '.$cls.' не найден или не валидный');
        }

        return new $cls();
    }

    /**
     * Инициализирует объект зпроса
     *
     * @throws \Exception
     */
    protected function initialize()
    {
        $key = strpos($this->uri, '?');
        $params = $key ? substr($this->uri, $key + 2) : null;
        $uriBlocks = explode('/', substr($this->uri, 0, $key ?: strlen($this->uri)));

        if (0 === strpos($this->uri, '/')) {
            array_shift($uriBlocks);
        }

        $this->prepareController($uriBlocks);
        $this->prepareAction($uriBlocks);

        if ($params) {
            $paramBlocks = explode('&', $params);
            $query = (new RequestInjector)->build()->getQueryParams();

            foreach ($paramBlocks as $param) {
                $val = explode('=', $param);

                $query[$val[0]] = $val[1];
            }

            // replace request
            $request = (new RequestInjector)->build();
            (new RequestInjector)->addRequirement('request', $request->withQueryParams(
                array_replace_recursive($request->getQueryParams(), $query)
            ));
        }
    }

    /**
     * Подготавливает контроллер
     *
     * @param $uriBlocks
     * @return void
     * @throws \ReflectionException
     */
    protected function prepareController(&$uriBlocks): void
    {
        $path = (new KernelInjector)->build()->getAppDir();
        $str = array_shift($uriBlocks);

        if (file_exists(str_replace('\\', '/', $path . '/controllers/' . ucfirst($str) . 'Controller.php'))) {
            $this->controller = $str;
        } else {
            $this->controller = 'default';

            array_unshift($uriBlocks, $str);
        }
    }

    /**
     * Подготавливает экшен
     *
     * @param $uriBlocks
     * @return void
     */
    protected function prepareAction(&$uriBlocks): void
    {
        $this->action = array_shift($uriBlocks) ?: 'index';
    }

    /**
     * Возвращает путь к контроллеру
     *
     * @return string
     */
    public function getCalculatePath()
    {
        return '\\App\\Controllers\\' . $this->getController();
    }

    /**
     * Возвращает контроллер
     *
     * @return string
     */
    public function getController(): string
    {
        return ucfirst($this->controller) . 'Controller';
    }

    /**
     * Возвращает экшен
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}