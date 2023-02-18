<?php

namespace Framework\Web;

use Framework\Web\Interfaces\ICookie;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Cookie
 */
class Cookie implements ICookie
{
    /** @var ServerRequestInterface $request */
    protected ServerRequestInterface $request;

    /**
     * Конструктор кук
     *
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Возвращает куку
     *
     * @param string $name
     * @return mixed|null
     */
    public function get(string $name)
    {
        $cookie = $this->request->getCookieParams();

        return array_key_exists($name, $cookie) ? $cookie[$name] : null;
    }

    /**
     * Удаляет куку
     *
     * @param string $name
     * @return bool
     */
    public function del(string $name): bool
    {
        if ($this->exists($name)) {
            return $this->set($name, false, time() - 3600);
        }

        return false;
    }

    /**
     * Проверяет куку на существование
     *
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool
    {
        return array_key_exists($name, $this->request->getCookieParams());
    }

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
    public function set(string $name, $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = true): bool
    {
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }
}