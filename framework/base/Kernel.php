<?php

namespace Framework\Base;

use ReflectionObject;

/**
 * Класс ядра приложения
 */
class Kernel implements KernelInterface
{
    /** @const string Версия фреймворка */
    const VERSION = '1.0';

    /** @var string $appDir */
    protected string $appDir;
    /** @var string $webDir */
    protected string $webDir;

    /** @var bool $debug Включить вывод ошибок */
    private bool $debug = true;
    /** @var string $environment Среда приложения */
    private string $environment = 'dev';
    /** @var float $startTime Время старта приложения */
    private float $startTime;

    /** @var bool $loaded Загружен ли фреймворк */
    private bool $loaded;

    /**
     * Конструктор
     *
     * @param $environment
     * @param bool $debug
     */
    public function __construct($environment, bool $debug = false)
    {
        $this->webDir = getenv('DOCUMENT_ROOT');
        $this->environment = (string) $environment;
        $this->debug = (bool) $debug;
        $this->loaded = false;

        ini_set('display_errors', (integer) $this->debug);
        ini_set('log_errors', (integer) $this->debug);

        if ($this->debug) {
            ini_set('error_reporting', -1);
            $this->startTime = microtime(true);
        }
    }

    /**
     * Клонирование ядра
     *
     * @access public
     * @return void
     */
    public function __clone()
    {
        if ($this->debug) {
            $this->startTime = microtime(true);
        }

        $this->loaded = false;
    }

    /**
     * Загрузка зависимостей в кэш
     *
     * @return void
     */
    final public function loadInjectorsFromCache(): void
    {
        // test
        $injectors = ['name' => 'value_object'];

        $baseInjector = new Injector;
        foreach ($injectors as $name => $injector) {
            $baseInjector->addRequirement($name, $injector);
        }
    }

    /**
     * Возвращаем путь к корню приложения
     *
     * @return mixed
     */
    public function getAppDir()
    {
        if (!$this->appDir) {
            $this->appDir = realpath(dirname((new ReflectionObject($this))->getFileName()));
        }

        return $this->appDir;
    }

    /**
     * Возвращаем путь к корню клиентской папки web
     *
     * @return string
     */
    public function getWebDir(): string
    {
        return $this->webDir;
    }

    /**
     * Возвращаем настройки приложения
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->getAppDir().'/configs/index.php';
    }

    /**
     * Возвращаем среду окружения
     *
     * @return mixed
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Возвращаем включен ли вывод ошибок или нет
     *
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Проверка на доступность консоли
     *
     * @return bool
     */
    public function isCli(): bool
    {
        return PHP_SAPI === 'cli';
    }

    /**
     * Возвращаем время старта приложения
     *
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Возвращаем путь к папке логов
     *
     * @return string
     */
    public function getLogDir(): string
    {
        return $this->getAppDir().'/logs';
    }

    /**
     * Возвращаем путь к папке кэша
     * @return string
     */
    public function getCacheDir(): string
    {
        return $this->getAppDir().'/cache/'.$this->getEnvironment();
    }
}