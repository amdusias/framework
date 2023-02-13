<?php

namespace Framework;

use App\Kernel;
use Framework\Base\Config;

/**
 * Class Framework
 */
final class Framework extends Kernel
{
    /** @var string версия приложения */
    public static string $version = '1.0';
    /** @var Framework $app синглтон приложения */
    protected static $app;
    /** @var array настройки приложения */
    public array $config;

    /**
     * Конструктор приложения
     */
    protected function __construct()
    {
        $this->config = Config::getInstance()->loadConfig($this->getConfigDir());
    }

    private function __clone() {}

    public function __wakeup() {}

    /**
     * Возвращает экземпляр одиночки класса приложения
     *
     * @return Framework
     */
    public static function getInstance(): Framework
    {
        if (self::$app === null) {
            self::$app = new Framework();
        }

        return self::$app;
    }

    /**
     * Запуск фреймворка
     */
    public function run()
    {

    }
}