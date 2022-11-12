<?php

namespace Framework\Base;

use Exception,
    ReflectionClass,
    ReflectionMethod;

/**
 * Класс зависимостей DI
 */
class Injector implements InjectorInterface
{
    /** @var array $CONFIG Настройки */
    private static array $CONFIG = [];
    /** @var array $INJECTS Настройки зависимостей */
    private static array $INJECTS = [];

    /**
     * Конструктор
     *
     * @param string $configPath
     */
    public function __construct(string $configPath = '')
    {
        if ($configPath !== '' && file_exists($configPath)) {
            self::$CONFIG = array_merge_recursive(self::$CONFIG, require $configPath);
        }
    }

    /**
     * Вернем строителю null по умолчанию
     *
     * @return null
     */
    public function build()
    {
        return null;
    }

    /**
     * Возвращаем данные о настройке
     *
     * @param string $name
     * @return string|null
     */
    public function param(string $name): ?string
    {
        return array_key_exists($name, self::$CONFIG) ? self::$CONFIG[$name] : null;
    }

    /**
     * Добавляем требование к зависимости
     *
     * @param $name
     * @param $component
     * @return void
     */
    public function addRequirement($name, $component): void
    {
        if (is_object($component)) {
            self::$INJECTS[$name] = $component;
        } else {
            self::$CONFIG[$name] = $component;
        }
    }

    /**
     * Создаем объект зависимости
     *
     * @param $name
     * @return false|mixed
     * @throws \ReflectionException
     */
    protected function get($name)
    {
        if (!empty(self::$CONFIG[$name])) {
            return self::$CONFIG[$name];
        }

        if (!empty(self::$INJECTS[$name])) {
            return self::$INJECTS[$name];
        }

        if (!empty(self::$CONFIG['components'][$name])) {
            return $this->loadInjection($name);
        }

        return false;
    }

    /**
     * Загрузка зависимости
     *
     * @param $name
     * @throws \ReflectionException
     * @return false
     */
    private function loadInjection($name): bool
    {
        $options = self::$CONFIG['components'][$name];

        if (empty($options['class']) || !class_exists($options['class'])) {
            return false;
        }

        $className = $options['class'];

        $options['arguments'] = !empty($options['arguments']) ? $this->buildParams($options['arguments']) : [];
        $options['property'] = !empty($options['property']) ? $this->buildParams($options['property']) : [];
        $options['calls'] = !empty($options['calls']) ? $this->buildCalls($options['calls']) : [];

        self::$INJECTS[$name] = $this->makeObject($className, $options['arguments']);
        if (!self::$INJECTS[$name]) {
            return false;
        }

        if (!empty($options['property'])) { // load properties
            foreach ($options['property'] as $property => $value) {
                if (property_exists(self::$INJECTS[$name], $property)) {
                    self::$INJECTS[$name]->$property = $value;
                }
            }
        }

        if (!empty($options['calls'])) {
            foreach ($options['calls'] as $method => $arguments) {
                if (method_exists(self::$INJECTS[$name], $method)) {
                    $reflectionMethod = new \ReflectionMethod($className, $method);
                    if ($reflectionMethod->getNumberOfParameters() === 0) {
                        self::$INJECTS[$name]->$method();
                    } else {
                        call_user_func_array([self::$INJECTS[$name], $method], $arguments);
                    }
                }
            }
        }

        return self::$INJECTS[$name];
    }

    /**
     * Создаем параметры класса из массива
     *
     * @param array $params
     * @return array
     */
    private function buildParams(array $params): array
    {
        foreach ($params AS $key => &$val) {
            if (is_string($params[$key]) && (str_starts_with($val, '@'))) {
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
     * Создаем аргументы передаваемые в новый объект
     *
     * @param array $params
     * @return array
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
     * Создаем объект класса с аргументами
     *
     * @param $className
     * @param array $arguments
     * @return false|mixed|object|null
     */
    private function makeObject($className, array $arguments = []): mixed
    {
        try {
            $reflection = new ReflectionClass($className);
            $reflectionMethod = new ReflectionMethod($className, '__construct');

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
