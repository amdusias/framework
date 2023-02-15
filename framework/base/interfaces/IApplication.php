<?php

namespace Framework\Base\Interfaces;

use Framework\Mvc\Interfaces\IResolver;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface IApplication
 */
interface IApplication
{
    /**
     * Отправляет запрос
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    public function send(ResponseInterface $response);

    /**
     * Возвращает резольвер
     *
     * @return IResolver
     */
    public function getResolver(): IResolver;
}