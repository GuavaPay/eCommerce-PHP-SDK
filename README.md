# GuavaPay eCommerce SDK for PHP

[![Total Downloads](https://img.shields.io/packagist/dt/guavapay/epg.svg?style=flat)](https://packagist.org/packages/guavapay/epg)

The **GuavaPay eCommerce SDK for PHP** makes it easy for developers to integrate [GuavaPay][guavapay] Electronic Payment Gateway in their PHP code. You can
get started in minutes by installing the SDK through Composer.

Jump To:
* [Getting Started](#getting-started)
* [Examples](#quick-examples)

## Getting Started

1. **Sign up for GuavaPay** – To begin, at first you need to sign up for an GuavaPay merhant account and retrieve your credentials.
2. **Minimum requirements** – To run the SDK, your system will need to meet the minimum requirements including having **PHP >= 8.0**.
   We highly recommend having it compiled with the cURL extension.
3. **Install the SDK** – Using [composer] is the recommended way to install the SDK for your application. The SDK is available via [Packagist]. If Composer is installed, you can run the following in the base directory of your project to add the SDK as a dependency:
   ```
   composer require guavapay/epg
   ```
4. **Using the SDK** – The best way to become familiar with how to use the SDK is to read the User Guide. The
   Examples section will help you become familiar with the basic concepts.

## Quick Examples
### Create EPG instance
```
<?php
require_once('./vendor/autoload.php');

use GuavaPay\EPG;

$epg = new EPG('USER', 'SECRET', 'BANK_ID', 'SERVICE_ID');
```

After creating an instance of the ```EPG``` object, you can easily call methods. Examples are below.

### Create order on EPG
In order to accept payments, you need to create an order on the Electronic Payment Gateway (EPG). To achieve this, you need to call ```createOrder()``` method from the SDK.

```
...
use GuavaPay\Exception\GuavaEcomException;
use GuavaPay\Exception\GuavaClientException;

try {
    $order =  $epg->createOrder(time(), 100, 978, 'http://example.com/paymentResult');
    var_dump($order->getOrderId(), $order->getFormUrl());
} catch (GuavaEcomException $e) {
    // Logical error occured
    echo $e->getMessage();
} catch (GuavaClientException $e) {
    // Unable to send request to the EPG server
    echo $e->getMessage();
}
```

### Get order status from EPG
In order to check status of your order on the Electronic Payment Gateway (EPG), you need to call ```getOrderStatus()``` method from the SDK using the status code which was provided during the integration process with GuavaPay.

```
...
use GuavaPay\Exception\GuavaEcomException;
use GuavaPay\Exception\GuavaClientException;

try {
    $orderInfo = $epg->getOrderStatus('84c5387a-7824-742b-9567-0c1a0e7e1e23', '013');
    var_dump($orderInfo->getStatus(), $orderInfo->getIsSuccess(), $orderInfo->getAmount());
} catch (GuavaEcomException $e) {
    // Logical error occured
    echo $e->getMessage();
} catch (GuavaClientException $e) {
    // Unable to send request to the EPG server
    echo $e->getMessage();
}
```
### Get merchant available balance
In order to check available funds on your merchant account, you need to call ```getBalanceStatus()``` method from the SDK using the status code which was provided during the integration process with GuavaPay.

```
...
use GuavaPay\Exception\GuavaEcomException;
use GuavaPay\Exception\GuavaClientException;

try {
    var_dump($epg->getBalanceStatus(978, '013')->getAmount()); // returns float(133.74)
} catch (\GuavaPay\Exception\GuavaEcomException $e) {
    // Logical error occured
    echo $e->getMessage();
} catch (\GuavaPay\Exception\GuavaClientException $e) {
    // Unable to send request to the EPG server
    echo $e->getMessage();
}
```

### Get 3D Secure version
To check version of the 3D secure on the customer's card, you need to call ```check3dsVersion()``` method from the SDK and pass the ```CardConfig``` object in it.

```
...
use GuavaPay\Exception\GuavaEcomException;
use GuavaPay\Exception\GuavaClientException;
use GuavaPay\Config\CardConfig;

try {
    $expiry = DateTime::createFromFormat('m/Y', '06/2026');
    $cardConfig = new CardConfig('5373611014639050', $expiry, '652', 'CARD HOLDER');
    var_dump($epg->check3dsVersion('84c5387a-7824-742b-9567-0c1a0e7e1e23', $cardConfig)->getVersion()); // returns int(2)
} catch (\GuavaPay\Exception\GuavaEcomException $e) {
    // Logical error occured
    echo $e->getMessage();
} catch (\GuavaPay\Exception\GuavaClientException $e) {
    // Unable to send request to the EPG server
    echo $e->getMessage();
}

[guavapay]: https://guavapay.com/
[composer]: https://getcomposer.org/download/
[packagist]: https://packagist.org/packages/guavapay/epg