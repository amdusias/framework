<?php

namespace Framework;

use App\Kernel;
use Framework\Mvc\MvcApplication;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Framework
 */
final class Framework extends Kernel
{
    /** @var string версия приложения */
    public static string $version = '1.0';
    /** @var Framework $app объект приложения */
    protected static $app;
    /** @var Kernel объект ядра приложения */
    private Kernel $kernel;

    /**
     * Конструктор приложения
     */
    public function __construct(private array $config)
    {
        parent::__construct($this->config);

        $this->kernel = new Kernel($this->config);
    }

    /**
     * Возвращает экземпляр одиночки класса приложения
     *
     * @param $config
     * @return Framework
     */
    public static function getInstance($config): Framework
    {
        if (self::$app === null) {
            self::$app = new Framework($config);
        }

        return self::$app;
    }

    /**
     * Запуск фреймворка
     */
    public function run(ServerRequestInterface $server)
    {
        $app = new MvcApplication($this->kernel);
        $app->run($server);
    }
}