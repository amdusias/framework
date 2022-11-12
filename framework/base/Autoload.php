<?php

namespace Framework\Base;

/**
 * Класс автозагрузчик
 */
class Autoload
{
    /** @var array $aliases Список алиасов (псевдонимов) автозагрузчика */
    private static array $aliases = [];

    /**
     * Создаем новый алиас (псевдоним)
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
     * Возвращаем алиас (псевдоним)
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
     * Перобразуем первые буквы пространства имен в нижний регистр
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
     * Получаем полный путь к классу
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
     * Загрузка классов
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