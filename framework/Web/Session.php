<?php

namespace Framework\Web;

use Framework\Web\Interfaces\ISession;

/**
 * Class Session
 */
class Session extends \stdClass implements ISession
{
    /**
     * Конструктор сессий
     */
    public function __construct(bool $autoStart = false)
    {
        if ($autoStart === true) {
            $this->create();
        }
    }

    /**
     * Запускает систему сессий
     *
     * @return void
     */
    public function create(): void
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            session_start();
        }
    }

    /**
     * Уничтожает сессии
     *
     * @return void
     */
    public function destroy(): void
    {
        if (PHP_SESSION_ACTIVE === session_status()) {
            session_unset();
            session_destroy();
        }
    }

    /**
     * Возвращает сессию
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : null;
    }

    /**
     * Создает сессию
     *
     * @param string $name
     * @param $value
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Проверяет сессию на существование
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return array_key_exists($name, $_SESSION);
    }

    /**
     * Удаляет сессию
     *
     * @param string $name
     * @return void
     */
    public function __unset(string $name): void
    {
        if (array_key_exists($name, $_SESSION)) {
            unset($_SESSION[$name]);
        }
    }
}