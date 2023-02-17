# Информация о роутах

<p>Настройки компонента <b>routes</b>, роуты находятся по пути <b>app/config/components/router.php</b></p>

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