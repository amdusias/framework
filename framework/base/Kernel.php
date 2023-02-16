<?php

namespace Framework\Base;

use Exception;
use Framework\Base\Interfaces\IKernel;
use Psr\Http\Message\ServerRequestInterface;

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
    /** @var bool $loaded флаг загрузки приложения */
    private bool $loaded;

    /**
     * Конструктор ядра
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->publicDir = getenv('DOCUMENT_ROOT');
        $this->debug = filter_var($config['debug'], FILTER_VALIDATE_BOOLEAN);
        $this->environment = $config['env'];
        $this->loaded = false;

        ini_set('display_errors', (integer) $this->debug);
        ini_set('log_errors', (integer) $this->debug);

        if ($this->debug) {
            ini_set('error_reporting', -1);
            $this->startTime = microtime(true);
        }
    }

    /**
     * Клонирует ядро
     *
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
     * Загружает зависимости (тест)
     *
     * @return void
     */
    final public function loadInjectorsFromCache(): void
    {
        $injectors = ['name' => 'value_object'];

        $baseInjector = new Injector;
        foreach ($injectors as $name => $injector) {
            $baseInjector->addRequirement($name, $injector);
        }
    }

    /**
     * Запускает ядро
     *
     * @param ServerRequestInterface $request
     * @return void
     */
    public function initialize(ServerRequestInterface $request)
    {
        $inject = new Injector($this->getConfigFile());
        $inject->addRequirement('kernel', $this);
        $inject->addRequirement('request', $request);

        $dispatcherInjector = new DispatcherInjector;

        try {
            $dispatcher = $dispatcherInjector->build();
        } catch (Exception $e) {
            $dispatcher = new Dispatcher;
            $dispatcherInjector->addRequirement('dispatcher', $dispatcher);
        }

        $dispatcher->signal('kernel.boot', ['injector' => $inject]);
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
     * Возвращает путь к файлу настроек приложения
     *
     * @return string
     */
    public function getConfigFile(): string
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
        return $this->publicDir . '/public';
    }

    /**
     * Возвращает статус дебаг режима
     *
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }
}