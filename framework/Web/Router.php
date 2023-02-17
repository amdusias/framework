<?php

namespace Framework\Web;

use Framework\Web\Interfaces\IRouter;

/**
 * Class Router
 */
class Router implements IRouter
{
    /** @var array $routes карта роутов */
    public array $routes = [];

    /**
     * Конструктор роутера
     *
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Парсит url адрес
     *
     * @param string $uri
     * @param string $method
     * @return string|void
     */
    public function parse(string $uri, string $method = 'GET')
    {
        // дефолтный путь
        if ($uri === '/' || $uri === '' || $uri === '/default') {
            return '/default';
        }

        // проверяем роуты
        foreach ($this->routes as $condition => $config) {
            if (is_array($config) && !empty($config['route'])) {
                if (!empty($config['verb']) && ($config['verb'] !== $method)) {
                    continue;
                }

                $replacement = $config['route'];
            } elseif (is_string($config)) {
                $replacement = $config;
            } else {
                continue;
            }

            if ($uri === $condition) {
                return $replacement;
            }

            if ($validated = $this->validatedRule($uri, $condition, $replacement)) {
                return $validated;
            }
        }

        return $uri;
    }

    /**
     * Валидирует правила роута
     *
     * @param string $uri
     * @param string $pattern
     * @param string $replacement
     * @return false|string
     */
    private function validatedRule(string $uri, string $pattern, string $replacement)
    {
        $uriBlocks = explode('/', trim($uri, '/'));
        $patBlocks = explode('/', trim($pattern, '/'));
        $repBlocks = explode('/', trim($replacement, '/'));

        if (count($uriBlocks) !== count($patBlocks)) {
            return false;
        }


        $attributes = $this->parseUri($uriBlocks, $patBlocks);
        if (!$attributes) {
            return false;
        }

        $result = $this->buildResult($attributes, $repBlocks);
        if ($result === null || $result === false) {
            return false;
        }

        if (!$attributes) {
            return $result;
        }

        $result .= '?';
        foreach ($attributes AS $key => $val) {
            if ($key !== $val) {
                $result .= '&'.$key.'='.$val;
            }
        }

        return $result;
    }

    /**
     * Проверяет совпадение реального пути с путем из адресной строки
     *
     * @param array $uriBlocks
     * @param array $patBlocks
     * @return array|null
     */
    private function parseUri(array $uriBlocks = [], array $patBlocks = [])
    {
        $attr = [];

        foreach (range(0, count($uriBlocks) - 1) AS $i) {
            if (0 === strpos($patBlocks[$i], '<')) {
                $cut = strpos($patBlocks[$i], ':');

                if (preg_match('/'.substr($patBlocks[$i], $cut + 1, -1).'/', $uriBlocks[$i])) {
                    $attr[substr($patBlocks[$i], 1, $cut - 1)] = $uriBlocks[$i];
                } else {
                    return null;
                }

            } elseif ($uriBlocks[$i] !== $patBlocks[$i]) {
                return null;
            } else {
                $attr[$uriBlocks[$i]] = $patBlocks[$i];
            }
        }

        return $attr;
    }

    /**
     * Строит запрос
     *
     * @param $attr
     * @param $repBlocks
     * @return false|string|null
     */
    private function buildResult(&$attr, $repBlocks)
    {
        $result = null;

        foreach ($repBlocks AS $value) {
            if (0 !== strpos($value, '<')) {
                $result .= '/'.$value;

                unset($attr[$value]);
            } else {
                $element = substr($value, 1, -1);

                if (!empty($attr[$element])) {
                    $result .= '/'.$attr[$element];

                    unset($attr[$element]);
                } else {
                    return false;
                }
            }
        }

        return $result;
    }
}