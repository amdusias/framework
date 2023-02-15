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
     * Главная страница
     */
    public function actionIndex(): View
    {
        return new View();
    }
}