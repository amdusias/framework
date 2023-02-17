<?php

namespace App\Controllers;

use App\Components\Controller;
use App\Components\View;
use Framework\Web\Injectors\SessionInjector;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
    /**
     * Возвращает дефолтное представление
     */
    public function actionIndex(): View
    {
        return new View();
    }
}