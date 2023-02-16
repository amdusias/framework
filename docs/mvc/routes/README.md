# Информация о роутах

Все роуты находятся по пути <b>app/configs/components/routes.php</b>

```php
<?php

return [
    'class' => '\Framework\Web\Router',
    'arguments' => [
        'routes' => [
            // здесь находится массив роутов
        ]
    ]
];
```

## Пример создания роута

```php
<?php

return [
    'class' => '\Framework\Web\Router',
    'arguments' => [
        'routes' => [
            '/test' => '/test/index'
        ]
    ]
];
```

## Расшифровка
```
/test - url адресной строки: /test
/test/index - (test) - контроллер, (index) - экшен
```