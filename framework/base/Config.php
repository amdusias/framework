<?php

namespace Framework\Base;

/**
 * Class Config
 */
class Config
{
    /** @var Config синглтон настроек приложения */
    private static $instance;

    /** @var array настройки приложения */
    private array $config;

    private function __clone() {}
    public function __wakeup() {}

    public function __destruct()
    {
        self::$instance = null;
    }

    /**
     * Возвращает экземпляр одиночки класса настроек фреймворка
     *
     * @return Config
     */
    public static function getInstance(): Config
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Загружает настройки
     *
     * @param string $path
     * @return array
     */
    public function loadConfig(string $path): array
    {
        $this->config = require_once $path;

        return $this->config;
    }
}