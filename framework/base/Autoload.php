<?php

namespace Framework\Base;

/**
 * Class Autoload
 */
class Autoload
{
    /** @var array список алиасов */
    private static array $aliases = [];

    /**
     * Создает новый алиас (псевдоним)
     *
     * @param $alias
     * @param $realPath
     * @return void
     */
    public static function setAlias($alias, $realPath)
    {
        if (is_string($alias) && is_string($realPath)) {
            self::$aliases[strtolower($alias)] = $realPath;
        }
    }

    /**
     * Возвращает алиас (псевдоним)
     *
     * @param $alias
     * @return false|mixed
     */
    public static function getAlias($alias)
    {
        $alias = strtolower($alias);

        return array_key_exists($alias, self::$aliases) ? self::$aliases[$alias] : false;
    }

    /**
     * Перобразует первые буквы алиаса в нижний регистр
     *
     * @param $path
     * @return string
     */
    private static function camelCaseToLowerNamespace($path): string
    {
        $classNameArr = array_map(function($val) {
            return lcfirst($val);
        }, explode('\\', $path));

        $classNameArr[] = ucfirst(array_pop($classNameArr));

        return implode('\\', $classNameArr);
    }

    /**
     * Получает полный путь к классу
     *
     * @param $className
     * @param string $extension
     * @return false|string
     */
    public static function getClassPath($className, string $extension = '.php')
    {
        $prefix = $className = self::camelCaseToLowerNamespace(str_replace('_', '\\', $className));

        while (false !== $position = strrpos($prefix, '\\')) {
            $prefix = substr($prefix, 0, $position);
            $alias = self::getAlias($prefix);

            if (!$alias) {
                continue;
            }

            $path = $alias.'\\'.substr($className, mb_strlen($prefix) + 1);
            $absolutePath = str_replace('\\', DIRECTORY_SEPARATOR, $path).$extension;

            if (is_readable($absolutePath)) {
                return $absolutePath;
            }
        }

        return false;
    }

    /**
     * Загружает класс
     *
     * @param $className
     * @return bool
     */
    public static function loader($className): bool
    {
        if ($path = self::getClassPath(ltrim($className, '\\'))) {
            require_once $path;

            return true;
        }

        return false;
    }
}