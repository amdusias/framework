<?php

namespace Framework\Mvc;

use Framework\Base\Application;
use Framework\Base\Injector;
use Framework\Mvc\Interfaces\IResolver;
use Framework\Web\Injectors\RequestInjector;
use Framework\Web\Injectors\ResponseInjector;
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
     * Запускает приложение при возникновении ошибки
     *
     * @param \Exception $e
     * @return ResponseInterface|void
     * @throws \Exception
     */
    protected function doException(\Exception $e)
    {
        $output = (new ResponseInjector)->build();

        $errorController = (new Injector)->param('errorController');
        $errorAction = (new Injector)->param('errorAction');

        if (!$errorController || !$errorAction) {
            $stream = $output->getBody();
            $stream->write('Настройка `errorController` или `errorAction` не настроена');

            return $output->withBody($stream);
        }

        $body = (new RequestInjector)->build()->getParsedBody();
        $body['error'] = $e;

        $request = (new RequestInjector)->build()->withParsedBody($body);
        (new Injector)->addRequirement('request', $request);

        $controller = $errorController;

        $result = new $controller(false);
        $result = $result->action($errorAction);

        if ($result instanceof ResponseInterface) {
            return $result;
        }

        $stream = $output->getBody();
        $stream->write((string)$result);

        return $output->withBody($stream);
    }
}