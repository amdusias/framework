<?php

namespace Framework\Base;

use Psr\Http\Message\ResponseInterface;

/**
 * Интерфейс для главного класса фреймворка
 */
interface ApplicationInterface
{
    /**
     * Отправляем запрос клиенту
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    public function send(ResponseInterface $response);

    /**
     * Завершаем приложение
     *
     * @return mixed
     */
    public function terminate();

    /**
     * @return ResolverInterface
     */
    public function getResolver(): ResolverInterface;
}