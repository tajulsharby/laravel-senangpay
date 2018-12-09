# Senang Pay

[![Build Status](https://travis-ci.org/jomos/senang-pay.svg?branch=master)](https://travis-ci.org/jomos/senang-pay)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Packagist](https://img.shields.io/packagist/v/jomos/senang-pay.svg)](https://packagist.org/packages/jomos/senang-pay)
[![Packagist](https://poser.pugx.org/jomos/senang-pay/d/total.svg)](https://packagist.org/packages/jomos/senang-pay)
[![Packagist](https://img.shields.io/packagist/l/jomos/senang-pay.svg)](https://packagist.org/packages/jomos/senang-pay)

Package description: This is a simple package to allow using Senangpay Payment Gateway API in Laravel Project

## Installation

Install via composer
```
composer require jomos/senang-pay
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
Jomos\SenangPay\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
Jomos\SenangPay\Facades\SenangPay::class,
```

### Publish Configuration File

```
php artisan vendor:publish --provider="Jomos\SenangPay\ServiceProvider" --tag="config"
```

## Usage

Step 1: Make sure you already register with Senangpay
Step 2: Login and get your Merchant ID and your Secret Key
        Then please enter this inside field for Return Url:
        'http://[YOUR DOMAIN NAME]/process-return-url'
        Then enter this in Return URL Parameters:
        '?status_id=[TXN_STATUS]&order_id=[ORDER_ID]&transaction_id=[TXN_REF]&message=[MSG]&hash=[HASH]'

Step 3: (assuming you already install this package and publish them)
        Go to config/senangpay.php and edit your Merchant Id and your Secret Key accordingly (which you get from step 2).

Step 4: In Your controller add this to your ordering processing method.

Example: After customer click checkout or pay, the form should submit via post to a controller method

```php

use Jomos/SenangPay/Senangpay;

class PaymentController extends Controller {
    
    public function processOrder(Request $request){
    
        // .. prior code usually on taking orders and save to orders table

        $payerName = $request->payer_name;
        $payerEmail = $request->payer_email; 
        $payerPhone = $rquest->payer_phone;
        $detail = 'Order For something something'; // Change to any title of this order
        $orderId = '1234567'; // Make sure it is a unique no and not a running number that payer can guest.
        $amount = '300'; // Equals to RM300.00

        Senangpay::setPaymentDetails( $payerName, $payerEmail, $payerPhone, $detail, $orderId, $amount );
        return Senangpay::processPayment();

    }

    public function processReturnUrl(Request $request){
    
        if(Senangpay::checkIfReturnHashCorrect( $request ) == true)
        {
		        $order = Order::find($request->order_id);

            if( $request->status_id == 1 )
            {
              $order->payment_status = 'Paid';
                    $order->senangpay_transaction_id = $request->transaction_id;
                    $order->confirm_payment_date = date('Y-m-d');
                    $order->save();
              return redirect()->to('success');

            } else {

              return redirect()->to('fail');

            }

        }


    }

}

```

Step 5: In routes/web.php add these 2 routes:

```php
    Route::post('process-order', 'PaymentController@processOrder');
    Route::get('process-return-url', 'PaymentController@processReturnUrl');

```

## Security

If you discover any security related issues, please email 
instead of using the issue tracker.

## Credits

- [](https://github.com/jomos/senang-pay)
- [All contributors](https://github.com/jomos/senang-pay/graphs/contributors)
