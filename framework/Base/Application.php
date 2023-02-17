<?php

namespace Framework\Base;

use Framework\Base\Injectors\DispatcherInjector;
use Framework\Base\Interfaces\IApplication;
use Framework\Base\Interfaces\IKernel;
use Framework\Mvc\Interfaces\IController;
use Framework\Web\Injectors\ResponseInjector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Application
 */
abstract class Application implements IApplication
{
    /** @var IKernel объект ядра $kernel */
    protected IKernel $kernel;

    /**
     * Конструктор
     *
     * @param IKernel $kernel
     */
    final public function __construct(IKernel $kernel)
    {
        $this->kernel = $kernel;

        $this->kernel->loadInjectorsFromCache();
    }

    /**
     * Запускает приложение
     *
     * @param ServerRequestInterface $server
     * @return void|null
     * @throws \Exception
     */
    final public function run(ServerRequestInterface $server)
    {
        $this->kernel->initialize($server);

        FatalError::register();

        try {
            return $this->doRun();
        } catch (\Exception $e) {
            if ($this->kernel->isDebug()) {
                (new DispatcherInjector)->build()->signal('kernel.exception', ['exception' => $e]);

                throw $e;
            }

            return $this->doException($e);
        }
    }

    /**
     * Запускает реально приложение
     */
    private function doRun()
    {
        $dispatcher = (new DispatcherInjector)->build();

        // возвращаем событие request
        if (($response = $dispatcher->signal('kernel.request')) instanceof ResponseInterface) {
            return $response;
        }

        $resolver = $this->getResolver();

        // возвращаем событие route
        if (($response = $dispatcher->signal('kernel.route', ['resolver' => $resolver])) instanceof ResponseInterface) {
            return $response;
        }

        /** @var IController $controller */
        $controller = $resolver->getApp();
        $action = $resolver->getAction();

        // возвращаем событие controller
        if (($response = $dispatcher->signal('kernel.controller', ['controller' => $controller, 'action' => $action])) instanceof ResponseInterface) {
            return $response;
        }

        $response = $controller->action((string) $action);

        if (!($response instanceof ResponseInterface)) {
            $responser = (new ResponseInjector)->build();
            $stream = $responser->getBody();
            $stream->write((string) $response);

            $response = $responser->withBody($stream);
        }

        $dispatcher->signal('kernel.response', ['response' => $response]);

        return $response;
    }

    /**
     * Запускает приложение при возникновении ошибки запуска
     *
     * @param \Exception $error
     * @return mixed
     */
    abstract protected function doException(\Exception $error);

    /**
     * Отправляет запрос клиенту
     *
     * @param ResponseInterface $response
     */
    public function send(ResponseInterface $response)
    {
        printf('%s%s', $response->getBody(), PHP_EOL);
    }
}