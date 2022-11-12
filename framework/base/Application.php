<?php

namespace Framework\Base;

use Psr\Http\Message\ResponseInterface,
    Psr\Http\Message\ServerRequestInterface;

/**
 * Главный класс для управления фреймворком
 */
abstract class Application implements ApplicationInterface
{
    /** @var KernelInterface Объект ядра $kernel */
    protected KernelInterface $kernel;

    /**
     * Конструктор
     *
     * @param KernelInterface|null $kernel
     */
    final public function __construct(KernelInterface $kernel = null)
    {
        $this->kernel = $kernel;

        if (!$this->kernel) {
            $this->kernel = new Kernel('dev', true);
        }

        $this->kernel->loadInjectorsFromCache();
    }

    /**
     * Запускаем приложение
     *
     * @param ServerRequestInterface $server
     * @return void
     */
    final public function run(ServerRequestInterface $server)
    {
        //
    }

    /**
     * Реальный запуск приложения
     *
     * @return void
     */
    private function doRun()
    {
        //
    }

    /**
     * Отправляем запрос клиенту
     *
     * @param ResponseInterface $response
     */
    public function send(ResponseInterface $response)
    {
        printf('%s%s', $response->getBody(), PHP_EOL);
    }

    /**
     * Закрываем приложение
     *
     * @return mixed|void
     */
    public function terminate()
    {
        //
    }
}