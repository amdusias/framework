<?php

namespace Framework\Mvc;

use Framework\Base\Application;
use Framework\Mvc\Interfaces\IResolver;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MvcApplication
 */
class MvcApplication extends Application
{
    /**
     * Возвращает резольвер
     *
     * @return IResolver
     */
    public function getResolver(): IResolver
    {
        return new MvcResolver;
    }

    /**
     * Отправляет запрос клиенту
     *
     * @param ResponseInterface $response
     */
    public function send(ResponseInterface $response)
    {
        header('HTTP/' . $response->getProtocolVersion() . ' ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());

        foreach ($response->getHeaders() as $header => $values) {
            header($header . ': ' . implode(', ', $values));
        }

        parent::send($response);
    }

    /**
     * Запускает приложение при возникновении ошибки запуска
     *
     * @param \Exception $e
     * @return void
     * @throws \Exception
     */
    protected function doException(\Exception $e)
    {
        //
    }
}