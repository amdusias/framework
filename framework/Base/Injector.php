<?php

namespace Framework\Base;

use Exception;
use Framework\Base\Interfaces\IInjector;

/**
 * Class Injector
 */
class Injector implements IInjector
{
    /** @var array $config настройки */
    private static $config = [];
    /** @var array $INJECTS настройки зависимостей */
    private static $injects = [];

    /**
     * Конструктор зависимостей
     *
     * @param string $configPath
     */
    public function __construct(string $configPath = '')
    {
        if ($configPath !== '' && file_exists($configPath)) {
            self::$config = array_merge_recursive(self::$config, require $configPath);
        }
    }

    /**
     * Строит зависимость
     *
     * @return null
     */
    public function build()
    {
        return null;
    }

    /**
     * Возвращает данные о настройке
     *
     * @param string $name
     * @return string
     */
    public function param(string $name)
    {
        return array_key_exists($name, self::$config) ? self::$config[$name] : null;
    }

    /**
     * Добавляет зависимость или настройку
     *
     * @param string $name
     * @param mixed $component
     * @return void
     */
    public function addRequirement($name, $component): void
    {
        if (is_object($component)) {
            self::$injects[$name] = $component;
        } else {
            self::$config[$name] = $component;
        }
    }

    /**
     * Возвращает зависимость
     *
     * @param string $name
     * @return boolean|mixed
     * @throws \ReflectionException
     */
    protected function get($name)
    {
        if (!empty(self::$config[$name])) {
            return self::$config[$name];
        }

        if (!empty(self::$injects[$name])) {
            return self::$injects[$name];
        }

        if (!empty(self::$config['components'][$name])) {
            return $this->loadInjection($name);
        }

        return false;
    }

    /**
     * Загружает зависимость
     *
     * @param $name
     * @throws \ReflectionException
     * @return boolean|mixed
     */
    private function loadInjection($name)
    {
        $options = self::$config['components'][$name];

        if (empty($options['class']) || !class_exists($options['class'])) {
            return false;
        }

        $className = $options['class'];

        $options['arguments'] = !empty($options['arguments']) ? $this->buildParams($options['arguments']) : [];
        $options['property'] = !empty($options['property']) ? $this->buildParams($options['property']) : [];
        $options['calls'] = !empty($options['calls']) ? $this->buildCalls($options['calls']) : [];

        self::$injects[$name] = $this->makeObject($className, $options['arguments']);

        if (!self::$injects[$name]) {
            return false;
        }

        if (!empty($options['property'])) {
            foreach ($options['property'] as $property => $value) {
                if (property_exists(self::$injects[$name], $property)) {
                    self::$injects[$name]->$property = $value;
                }
            }
        }

        if (!empty($options['calls'])) {
            foreach ($options['calls'] as $method => $arguments) {
                if (method_exists(self::$injects[$name], $method)) {
                    $reflectionMethod = new \ReflectionMethod($className, $method);
                    if ($reflectionMethod->getNumberOfParameters() === 0) {
                        self::$injects[$name]->$method();
                    } else {
                        call_user_func_array([self::$injects[$name], $method], $arguments);
                    }
                }
            }
        }

        return self::$injects[$name];
    }

    /**
     * Создает аргументы из массива настройки зависимости
     *
     * @param array $params
     * @return array
     * @throws \ReflectionException
     */
    private function buildParams(array $params): array
    {
        foreach ($params AS $key => &$val) {
            if (is_string($params[$key]) && (0 === strpos($val, '@'))) {
                if ($val === '@this') {
                    $val = $this;
                } else {
                    $val = $this->get(substr($val, 1));
                }
            }
        }

        return $params;
    }

    /**
     * Создает аргументы передаваемые в созданный объект
     *
     * @param array $params
     * @return array
     * @throws \ReflectionException
     */
    private function buildCalls(array $params): array
    {
        $callers = [];

        if (!is_array($params[0])) {
            $params = [
                $params
            ];
        }

        foreach ($params as $arguments) {
            if (is_string($arguments[0])) {
                if (!empty($arguments[1]) && is_array($arguments[1])) {
                    $callers[$arguments[0]] = $this->buildParams($arguments[1]);
                } else {
                    $callers[$arguments[0]] = null;
                }
            }
        }

        return $callers;
    }

    /**
     * Создает объект класса с аргументами
     *
     * @param $className
     * @param array $arguments
     * @return false|mixed|object|null
     */
    private function makeObject($className, array $arguments = []): mixed
    {
        try {
            $reflection = new \ReflectionClass($className);
            $reflectionMethod = new \ReflectionMethod($className, '__construct');

            if ($reflectionMethod->getNumberOfParameters() === 0) {
                return new $className;
            } else {
                return $reflection->newInstanceArgs($arguments);
            }
        } catch (Exception $e) {
            return false;
        }
    }
}