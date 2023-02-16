# Создание экшена в контроллере

Все контроллеры находятся в папке <b>app/controllers</b>
<p>Пример создания экшена в контроллере:</p>

```php
<?php

use App\Components\Controller;

/**
 * Class TestController
 */
class TestController extends Controller
{
    public function actionIndex()
    {
        // Todo
    }
}
```