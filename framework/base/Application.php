<?php

namespace Framework\Base;

use Framework\Base\Interfaces\IApplication;
use Framework\Base\Interfaces\IKernel;
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
     * Запускаем приложение
     *
     * @param ServerRequestInterface $server
     * @return void|null
     * @throws \Exception
     */
    final public function run(ServerRequestInterface $server)
    {
        $this->kernel->initialize($server);

        try {
            return $this->doRun();
        } catch (\Exception $e) {
            if ($this->kernel->isDebug()) {
                (new DispatcherInjector)->build()->signal('kernel.exception', ['exception' => $e]);

                throw $e;
            }
        }
    }

    /**
     * Реальный запуск приложения
     */
    private function doRun()
    {
        $dispatcher = (new DispatcherInjector)->build();

        // показываем событие request
        if (($response = $dispatcher->signal('kernel.request')) instanceof ResponseInterface) {
            return $response;
        }
    }

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