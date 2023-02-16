# Создание представления в контроллере

<p>Все представления находятся в папке <b>app/views</b></p>

<p>Пример созданного представления в контроллере:</p>

```php
<?php

use App\Components\Controller;

/**
 * Class TestController
 */
class TestController extends Controller
{
    /**
    * Созданный экшен в котором 
    * возвращается представление
    * 
    * @return View
    */
    public function actionIndex()
    {
        return new View();
    }
}
```

## Дополнительная информация

Путь представления формируется из названия контроллера и экшена

Представление контроллера <b>TestController</b> вызываемое в экшене <b>actionIndex</b> будет находится по адресу: 
<b>app/view/test/index.php</b>

## Передача параметров в представление

Пример передачи параметров в представление:

```php
<?php

use App\Components\Controller;

/**
 * Class TestController
 */
class TestController extends Controller
{
    /**
    * Созданный экшен в котором 
    * возвращается представление
    * 
    * @return View
    */
    public function actionIndex()
    {
        $view = new View();
        
        // передача параметров
        $view->addParameters([
            'test' => 123
        ]);
        
        return $view;
    }
}
```

## Пример вывода параметра в представлении

```php
<?php php echo $test; ?>
```
