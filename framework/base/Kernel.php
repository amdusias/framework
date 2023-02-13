<?php

namespace Framework\Base;

use Framework\Base\Interfaces\IKernel;

/**
 * Class Kernel
 */
class Kernel implements IKernel
{
    /** минимальная версия php */
    const PHP_MIN = '8.0.0';

    /** @var string $appDir путь к папке приложения */
    protected $appDir;
    /** @var string $publicDir путь к клиентской папке */
    protected string $publicDir;
    /** @var string режим разработки */
    private string $environment = 'dev';
    /** @var bool флаг дебага */
    private bool $debug = false;
    /** @var float $startTime время старта приложения */
    private float $startTime;

    /**
     * Конструктор ядра
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->publicDir = getenv('DOCUMENT_ROOT');
        $this->debug = (bool) $config['debug'];
        $this->environment = $config['env'];

        ini_set('display_errors', (integer) $this->debug);
        ini_set('log_errors', (integer) $this->debug);

        if ($this->debug) {
            ini_set('error_reporting', -1);
            $this->startTime = microtime(true);
        }
    }

    /**
     * Возвращает путь к папке приложения
     *
     * @return string
     */
    public function getAppDir(): string
    {
        if (!$this->appDir) {
            $this->appDir = realpath(dirname((new \ReflectionObject($this))->getFileName()));
        }

        return $this->appDir;
    }

    /**
     * Возвращает путь к папке настроек приложения
     *
     * @return string
     */
    public function getConfigDir(): string
    {
        return $this->getAppDir() . '/configs/index.php';
    }

    /**
     * Возвращает путь к клиентской папке
     *
     * @return string
     */
    public function getPublicDir(): string
    {
        return $this->publicDir;
    }
}