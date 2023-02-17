<?php

namespace App\Components;

use Framework\Mvc\Controllers\ViewController;

/**
 * Class Controller
 */
class Controller extends ViewController
{
    /**
     * Конструктор контроллера
     */
    public function __construct()
    {
        $this->layout = 'default';

        parent::__construct();
    }
}