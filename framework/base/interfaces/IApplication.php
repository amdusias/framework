<?php

namespace Framework\Base\Interfaces;

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
}