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
```PHP
<?php
require_once('./vendor/autoload.php');

use GuavaPay\EPG;

$epg = new EPG('USER', 'SECRET', 'BANK_ID', 'SERVICE_ID');
```

After initializing an instance of the ```EPG``` object, you can easily call methods. Examples are below.

### Register order on EPG
In order to accept payments, it is essential to register an order on the Electronic Payment Gateway (EPG). This can be accomplished by invoking the ```createOrder()``` method from the SDK.

```PHP
...
use GuavaPay\Exception\GuavaEcomException;
use GuavaPay\Exception\GuavaClientException;

try {
    $orderId = '123456'; // The order ID from your database.
    $amount = 100; // The amount in cents for the payment.
    $currency = 978; // The currency code in ISO 4217 format (Euro in this case).
    $returnUrl = 'http://example.com/paymentResult'; // The URL where the customer will be redirected after a successful payment.
    
    // Create an order using the Electronic Payment Gateway (EPG).
    $order = $epg->createOrder($orderId, $amount, $currency, $returnUrl); 
    
    // Get the EPG order ID for reference. Example: 84c5387a-7824-742b-9567-0c1a0e7e1e23.
    var_dump($order->getOrderId()); 

    // Get the URL for the payment, where the customer should be redirected to make the payment.
    var_dump($order->getFormUrl()); 

} catch (GuavaEcomException $e) { 
    // An error occurred due to a logical issue during the payment process.
    echo $e->getMessage();
} catch (GuavaClientException $e) { 
    // Unable to send the request to the EPG server, possibly due to connectivity issues.
    echo $e->getMessage();
}
```

### Get order status from EPG
To verify the status of your order on the Electronic Payment Gateway (EPG), you can utilize the ```getOrderStatus()``` method from the SDK. To access the EPG order status, you will need to provide the status code received during the integration process with GuavaPay.

```PHP
...
use GuavaPay\Exception\GuavaEcomException;
use GuavaPay\Exception\GuavaClientException;

try {
    $epgOrder = '84c5387a-7824-742b-9567-0c1a0e7e1e23'; // EPG order ID
    $statusCode = '013'; // status code which was provided by GuavaPay during the integration
    $orderInfo = $epg->getOrderStatus($epgOrder, $statusCode);
    var_dump($orderInfo->getStatus(), $orderInfo->getIsSuccess(), $orderInfo->getAmount());
} catch (GuavaEcomException $e) { 
    // An error occurred due to a logical issue during the payment process.
    echo $e->getMessage();
} catch (GuavaClientException $e) { 
    // Unable to send the request to the EPG server, possibly due to connectivity issues.
    echo $e->getMessage();
}
```

### Get 3D Secure version
To check version of the 3D secure on the customer's card, you need to call ```check3dsVersion()``` method from the SDK, pass the EPG order ID (which was previously created) and ```CardConfig``` object in it.

```PHP
...
use GuavaPay\Exception\GuavaEcomException;
use GuavaPay\Exception\GuavaClientException;
use GuavaPay\Config\CardConfig;

try {
    $epgOrder = '84c5387a-7824-742b-9567-0c1a0e7e1e23'; // EPG order ID
    $expiry = DateTime::createFromFormat('m/Y', '06/2026');
    $cardConfig = new CardConfig('5373611014639050', $expiry, '652', 'CARD HOLDER');

    var_dump($epg->check3dsVersion($epgOrder, $cardConfig)->getVersion()); // returns int(2)
} catch (GuavaEcomException $e) { 
    // An error occurred due to a logical issue during the payment process.
    echo $e->getMessage();
} catch (GuavaClientException $e) { 
    // Unable to send the request to the EPG server, possibly due to connectivity issues.
    echo $e->getMessage();
}
```

### Payment
To charge the customer's card, at first you need to call ```check3dsVersion()``` method (example shown above) then call ```paymentRequest()``` method from the SDK and pass the ```CardConfig``` and ```DeviceConfig``` objects in it.

```PHP
...
use GuavaPay\Exception\GuavaEcomException;
use GuavaPay\Exception\GuavaClientException;
use GuavaPay\Config\CardConfig;
use GuavaPay\Config\DeviceConfig;

try {
    $expiry = DateTime::createFromFormat('m/Y', '06/2026');
    $cardConfig = new CardConfig('5373611014639050', $expiry, '652', 'CARD HOLDER');
    $deviceConfig = new DeviceConfig(true, 'ru-RU', 986, 1024, 0, false, 16);
    $payment = $epg->paymentRequest('84c5387a-7824-742b-9567-0c1a0e7e1e23', $cardConfig, $deviceConfig);

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

```PHP
...
use GuavaPay\Exception\GuavaEcomException;
use GuavaPay\Exception\GuavaClientException;

try {
    var_dump($epg->getBalanceStatus(978, '013')->getAmount()); // returns float(133.74)
} catch (GuavaEcomException $e) {
    // Logical error occured
    echo $e->getMessage();
} catch (GuavaClientException $e) {
    // Unable to send request to the EPG server
    echo $e->getMessage();
}
```

[guavapay]: https://guavapay.com/
[composer]: https://getcomposer.org/download/
[packagist]: https://packagist.org/packages/guavapay/epg