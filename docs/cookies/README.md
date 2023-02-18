# Куки

<p>Настройки компонента <b>cookie</b> находятся по пути <b>app/config/components/cookie.php</b></p>

## Создаем куку с ключом test

```php
use Framework\Web\Injectors\CookieInjector;

$cookie = (new CookieInjector())->build();
$cookie->set('test', 123);
```

## Возвращаем значение куки с ключом test

```php
use Framework\Web\Injectors\CookieInjector;

$cookie = (new CookieInjector())->build();

return $cookie->get('test');
```

## Проверяем существование куки с ключом test

```php
use Framework\Web\Injectors\CookieInjector;

$cookie = (new CookieInjector())->build();

if ($cookie->exists('test'))
{
    echo "Кука 'test' существует";
}
```

## Удаляем куку с ключом test

```php
use Framework\Web\Injectors\CookieInjector;

$cookie = (new CookieInjector())->build();
$cookie->del('test');
```