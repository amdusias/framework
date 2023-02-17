# Сессии

<p>Настройки компонента <b>session</b> находятся по пути <b>app/config/components/session.php</b></p>

## Создаем сессию с ключом test

```php
use Framework\Web\Injectors\SessionInjector;

$session = (new SessionInjector())->build();
$session->test = 123;
```

## Возвращаем значение сессии с ключом test

```php
use Framework\Web\Injectors\SessionInjector;

$session = (new SessionInjector())->build();

return $session->test;
```

## Проверяем существование сессии с ключом test

```php
use Framework\Web\Injectors\SessionInjector;

$session = (new SessionInjector())->build();

if (isset($session->test))
{
    echo "Сессия {$test} существует";
}
```

## Удаляем сессию с ключом test

```php
use Framework\Web\Injectors\SessionInjector;

$session = (new SessionInjector())->build();
unset($session->test);
```

## Удаляем все сессии

```php
use Framework\Web\Injectors\SessionInjector;

(new SessionInjector())->build()->destroy();
```