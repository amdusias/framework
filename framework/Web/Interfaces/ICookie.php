<?php

namespace Framework\Web\Interfaces;

/**
 * Interface ICookie
 */
interface ICookie
{
    /**
     * Возвращает куку
     *
     * @param string $name
     */
    public function get(string $name);

    /**
     * Удаляет куку
     *
     * @param string $name
     * @return bool
     */
    public function del(string $name): bool;

    /**
     * Проверяет куку на существование
     *
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool;

    /**
     * Сохраняет куку
     *
     * @param string $name
     * @param $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return bool
     */
    public function set(string $name, $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = true): bool;
}