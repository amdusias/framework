<?php

namespace Framework\Mvc;

use Framework\Base\Application;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MvcApplication
 */
class MvcApplication extends Application
{
    /**
     * Отправляем запрос
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
}