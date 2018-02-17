<p align="center">
  <img alt="logo" src="https://hsto.org/webt/0v/qb/0p/0vqb0pp6ntyyd8mbdkkj0wsllwo.png" width="70"  height="70" />
  <img alt="logo" src="https://habrastorage.org/webt/59/df/45/59df45aa6c9cb971309988.png" width="70"  height="70" />
</p>

# PHP-клиент для работы с сервисом `moneta.ru`

[![Version][badge_version]][link_packagist]
[![Build][badge_build]][link_build]
![StyleCI][badge_styleci]
[![Coverage][badge_coverage]][link_coverage]
[![Code Quality][badge_code_quality]][link_coverage]
[![License][badge_license]][link_license]
[![Issues][badge_issues]][link_issues]
[![Downloads][badge_downloads]][link_packagist]

Данный пакет является реализацией клиента для работы с сервисом `moneta.ru`,
значительно упрощающим работу с последним, предоставляя разработчику внятное API.

Все методы API сопровождены соответствующим `@phpdoc`.

## Установка

Для установки данного пакета выполните в терминале следующую команду

```shell
$ composer require avto-dev/monetaru-api-php "^1.0"
```

Или добавьте зависимость вручную в composer.json

> Для этого необходим установленный `composer`. Для его установки перейдите по [данной ссылке][getComposer].

> Обратите внимание на то, что необходимо фиксировать мажорную версию устанавливаемого пакета.

## Компоненты

Данный пакет состоит из следующих компонентов:

Название | Описание
-------: | :-------
[Клиент][client_v1] | Реализует методы обращения к сервису `moneta.ru`
[HTTP-клиент][http_client] | Реализует методы осуществления запросов по протоколу `http` *(используется по умолчанию его реализация [`guzzle`][http_client_guzzle])*
[Справочники][references] | Содержат основные значения *(такие как типы запросов идентификаторов и так далее)*
[Классы типов данных][data_types] | К которым автоматически приводятся возвращаемые от сервиса данные *(которые реализуют дополнительные методы-акцессоры)*, если это возможно.

## Жизненный цикл запроса

При создании инстанса клиента производится инициализация http-клиента и контейнеров-методов, каждый из которых отвечает за свою группу методов. Например методы работы со штрафами вызываются с помощью `$client->fines()->someMethodName()`, в то время как команды работы с платежами - `$client->payments()->someMethodName()` 

При вызове любого метода API производится проверка - включен ли режим тестирования (параметр конфигурации is_test), и если это так - то реальный запрос не выполняется, а возвращается контент ответа из заранее подготовленных шаблонов, давая возможность произвести интеграцию с сервисом `moneta.ru` даже не имея учетной записи.

В случае, если режим тестирования не активен - то производится запрос к сервису `moneta.ru`.
Если запрос завершился некорректным кодом, или в его процессе "что-то пошло не так" - будет брошено исключение.
Поэтому, во избежание "падения" ваших приложений - оборачивайте все вызовы клиента в блок:

```php 
try { 
    ...
} catch (\Exception $e) {
    ...
}
```

## Настройка

Для настройки работы клиента, в конструктор класса необходимо передать массив определённой структуры.

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

    // Авторизация в системе `moneta.ru`.
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

## Использование

Инициализация клиента:

```php
<?php

include 'vendor/autoload.php';

use AvtoDev\MonetaApi\Clients\MonetaApi;

// Обязательные настройки
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

### Примеры

#### Штрафы

##### Получение списка штрафов по номеру постановления:

```php
<?php
$fines = $moneta->fines()
    ->find()
    ->byUin(['132','321'])
    ->includePaid()
    ->exec();

var_dump($fines->totalAmount());
var_dump($fines->needToPayAmount());
```

##### Получение списка штрафов по номеру свидетельства о регистрации ТС:

```php
<?php
$fines = $moneta->fines()
    ->find()
    ->bySTS('132321123')
    ->includePaid()
    ->exec();

var_dump($fines->totalAmount());
```
##### Получение списка штрафов по номеру водительского удостоверения:

```php
<?php
$fines = $moneta->fines()
    ->find()
    ->byDriverLicense('132321123')
    ->includePaid()
    ->exec();

var_dump($fines->totalAmount());
var_dump($fines->needToPayAmount());
```

В ответ приходит объект класса [FineCollection][fine_collection].

#### Оплата

##### Выставление счета

```php
<?php

$invoice = $moneta->payments()
    ->invoice()
    ->setDestinationAccount('123456789')
    ->setAmount(200)
    ->setClientTransactionId('testId1')
    ->exec();
var_dump($invoice->getPaymentUrl());
```
В ответ приходит объект класса [Invoice][invoice].

##### Оплата штрафа

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

##### Перевод средств между счетами

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
В ответ приходит объект класса [Payment][payment].

##### Получение информации о переводе

```php
<?php

$info = $moneta->payments()
    ->getOperationDetails()
    ->byId($payment->getId())
    ->exec();

var_dump($info);
```

В ответ приходит объект класса [OperationDetails][operation_details].

## Поддержка и развитие

Если у вас возникли какие-либо проблемы по работе с данным пакетом, пожалуйста, создайте соответствующий `issue` в данном репозитории.

Если вы решите самостоятельно реализовать дополнительный функционал - создайте PR с соответствующими изменениями. Крайне желательно сопровождать PR соответствующими тестами, фиксирующими работу ваших изменений. После проверки и принятия изменений будет опубликована новая минорная версия.

## Лицензирование

Код данного пакета распространяется под лицензией **MIT**.

[badge_version]:https://img.shields.io/packagist/v/avto-dev/monetaru-api-php.svg?style=flat&maxAge=30
[badge_build]:https://scrutinizer-ci.com/g/avto-dev/monetaru-api-php/badges/build.png?b=master
[badge_styleci]:https://styleci.io/repos/112570841/shield?style=flat
[badge_coverage]:https://scrutinizer-ci.com/g/avto-dev/monetaru-api-php/badges/coverage.png?b=master
[badge_code_quality]:https://scrutinizer-ci.com/g/avto-dev/monetaru-api-php/badges/quality-score.png?b=master
[badge_license]:https://img.shields.io/packagist/l/avto-dev/monetaru-api-php.svg?style=flat&maxAge=30
[badge_issues]:https://img.shields.io/github/issues/avto-dev/monetaru-api-php.svg?style=flat&maxAge=30
[badge_downloads]:https://img.shields.io/packagist/dt/avto-dev/monetaru-api-php.svg?style=flat&maxAge=30
[link_packagist]:https://packagist.org/packages/avto-dev/monetaru-api-php
[link_build]:https://scrutinizer-ci.com/g/avto-dev/monetaru-api-php/build-status/master
[link_coverage]:https://scrutinizer-ci.com/g/avto-dev/monetaru-api-php/?branch=master
[link_license]:https://github.com/avto-dev/monetaru-api-php/blob/master/LICENSE
[link_issues]:https://github.com/avto-dev/monetaru-api-php/issues
[getcomposer]:https://getcomposer.org/download/
[client_v1]:./src/Clients/MonetaApi.php
[http_client]:./src/HttpClients/HttpClientInterface.php
[http_client_guzzle]:./src/HttpClients/GuzzleHttpClient.php
[fine_collection]:./src/Support/FineCollection.php
[invoice]:./src/Types/Invoice.php
[payment]:./src/Types/Payment.php
[operation_details]:./src/Types/OperationDetails.php
[references]:./src/References
[data_types]:./src/Types
[feature_test_file]:./tests/SomeFeatureTestsTest.php
