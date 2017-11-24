#Php-клиент для работы с сервисом МОНЕТА.РУ
[![Build Status](https://scrutinizer-ci.com/b/jetexe/moneta/badges/build.png?b=master&s=bbb8c18e28b3026fddc5c7b0da3d93b7f6f09b4f)](https://scrutinizer-ci.com/b/jetexe/moneta/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/b/jetexe/moneta/badges/coverage.png?b=master&s=0f43b6b366ec56d65105bddde8a550672924a02b)](https://scrutinizer-ci.com/b/jetexe/moneta/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/b/jetexe/moneta/badges/quality-score.png?b=master&s=cc57950413ce09737549b7cf829560fa09ab24b0)](https://scrutinizer-ci.com/b/jetexe/moneta/?branch=master)
![GitHub issues](https://img.shields.io/github/issues/avto-dev/monetaru-api-php.svg?style=flat&maxAge=30)

Данный пакет является реализацией клиента для работы с сервисом МОНЕТА.РУ,
значительно упрощающим работу с последним, предоставляя разработчику внятное API.

Все методы API сопровождены соответствующим `@phpdoc`.

##Установка
Для установки данного пакета выполните в терминале следующую комманду

```shell
$ composer require avto-dev/moneta-api-php "1.*"
```

Или добавьте зависимость вручную в composer.json

> Для этого необходим установленный `composer`. Для его установки перейдите по [данной ссылке][getComposer].

> Обратите внимание на то, что необходимо фиксировать мажорную версию устанавливаемого пакета.

##Компоненты

Данный пакет состоит из следующих компонентов:

Название | Описание
-------: | :-------
[Клиент][client_v1] | Реализует методы обращения к сервису МОНЕТА.РУ
[HTTP-клиент][http_client] | Реализует методы осуществления запросов по протоколу `http` *(используется по умолчанию его реализация [`guzzle`][http_client_guzzle])*
[Справочники][references] | Содержат основные значения *(такие как типы запросов идентификаторов и так далее)*
[Классы типов данных][data_types] | К которым автоматически приводятся возвращаемые от сервиса данные *(которые реализуют дополнительные методы-акцессоры)*, если это возможно.

##Жизненный цикл запроса

При создании инстанса клиента производится инициализация http-клиента и контейнеров-методов,
каждый из которых отвечает за свою группу методов. Например методы работы со штрафами вызываются с помощью
`$client->fines()->someMethodName()`, в то время как команды работы с платежами - `$client->payments()->someMethodName()` 

При вызове любого метода API производится проверка - включен ли режим тестирования
(параметр конфигурации is_test), и если это так - то реальный запрос не выполняется,
а возвращается контент ответа из заранее подготовленных шаблонов,
давая возможность произвести интеграцию с сервисом МОНЕТА.РУ даже не имея учетной записи.

В случае, если режим тестирования не активен - то производится запрос к сервису МОНЕТА.РУ.
Если запрос завершился некорректным кодом, или в его процессе "что-то пошло не так" - будет брошено исключение.
Поэтому, во избежание "падения" ваших приложений - оборачивайте все вызовы клиента в блок 

```php 
try { 
    ...
} catch (\Exception $e) {
    ...
}
```

##Настройка

Для настройки работы клиента, в контсруктор класса необходимо передать массив определённой структуры.
Полная структура описана ниже: 

```php
<?php
$configuration = [
     // Endpoint работы с Монетой.
    'endpoint'         =>  'https://service.moneta.ru:51443/services',

     // ИД ГБДД в системе Монета.
    'fine_provider_id' => '9171.1',

     // Счета.
    'accounts'         => [

         // Счета поставщика штрафов.
        'provider'   => [
            'id'     => '9171',
            'sub_id' => '1',
        ],

         // Счет для оплаты штрафов.
        'fines'      => [
            'id'       => 'Идентификатор счета',
            'password' => 'Платежный пароль',
        ],

        // Счет для хранения коммиссии.
        'commission' => [
            'id'       => 'Идентификатор счета',
            'password' => 'Платежный пароль',
        ],
    ],

    // Авторизация в системе МОНЕТА.РУ.
    'authorization'    => [
        'username' => 'Логин',
        'password' => 'Пароль',
    ],

     // Настройки http-клиентов.
    'http_clients'     => [
        'guzzle' => [
            'headers'     => [
                'Content-Type' => 'application/json;charset=UTF-8',
            ],
            'timeout'     => 30,
            'http_errors' => false,
        ],
    ],

    // Используемый http-клиент.
    'use_http_client'  => 'guzzle',

    //Если установлен в true, возвращает тестовые данные.
    'is_test'          => false,
    ];

```

##Использование

Инициализация клиента:

```php
<?php

include 'vendor/autoload.php';

use AvtoDev\MonetaApi\Clients\MonetaApi;

//обязательные настройки
$config = [
    'authorization'  => [
        'username' => 'username',
        'password' => 'password',
    ],
    'accounts'       => [
        'fines' => [
            'id'      => '123456789',            
        ],
        'commission' => [
            'id' => '987654321',
        ],
    ],

];

$moneta = new MonetaApi($config);

```

###Примеры

Перевод средств между счетами:

```php
<?php
$payment = $moneta->payments()->transfer()
    ->setAccountNumber('123456789')
    ->setPaymentPassword('123')
    ->setDestinationAccount('987654321')
    ->setAmount(200)
    ->exec();

var_dump($payment->isSuccessful());

```

Получение списка штрафов:

```php
<?php
$fines = $moneta->fines()
    ->find()
    ->byUin('132')
    ->includePaid()
    ->exec();

var_dump($fines->totalAmount());

```

Оплата штрафа:

```php
<?php
$fine = $fines->current();
$payment = $moneta->payments()
    ->payOne($fine)
    ->setPayerPhone('89222222222') //Обязательно
    ->setPayerFio('Тестов тест тестович') //Обязательно
    ->exec();
var_dump($payment->isSuccessful());
```

## Обратная связь и поддержка

Если вы обнаружите какие-либо проблемы при работе с данным пакетом,
либо у вас появятся пожелания или необходимость в каком-либо дополнительном методе то,
пожалуйста, создайте соответствующий `issue` в данном репозитории.

-----

Лицензия: **MIT**

[client_v1]:./src/Clients/MonetaApi.php
[http_client]:./src/HttpClients/HttpClientInterface.php
[http_client_guzzle]:./src/HttpClients/GuzzleHttpClient.php
[references]:./src/References
[data_types]:./src/Types
[feature_test_file]:./tests/SomeFeatureTestsTest.php
[getcomposer]:https://getcomposer.org/download/