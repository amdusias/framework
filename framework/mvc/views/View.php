<?php

namespace Framework\Mvc\Views;

use Framework\Mvc\Interfaces\IView;

/**
 * Class View
 */
abstract class View implements IView
{
    /** @var array $params параметры передаваемые в представление */
    public array $params = [];

    /**
     * @result void
     */
    public function __construct()
    {
    }
}