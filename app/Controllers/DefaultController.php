<?php

namespace App\Controllers;

use App\Components\Controller;
use App\Components\View;

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