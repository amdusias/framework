<?php

namespace Framework\Web\Interfaces;

/**
 * Interface ISession
 */
interface ISession
{
    /**
     * Запускает систему сессий
     *
     * @return void
     */
    public function create(): void;

    /**
     * Уничтожает сессии
     *
     * @return void
     */
    public function destroy(): void;

    /**
     * Возвращает сессию
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed;

    /**
     * Создает сессию
     *
     * @param string $name
     * @param $value
     * @return void
     */
    public function __set(string $name, $value): void;

    /**
     * Проверяет сессию на существование
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool;

    /**
     * Удаляет сессию
     *
     * @param string $name
     * @return void
     */
    public function __unset(string $name): void;
}