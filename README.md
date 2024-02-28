## Thawani Manager 

Thawani Manager is a Laravel package designed to simplify interactions with the Thawani payment gateway API, enabling easy integration of Thawani payment solutions into your Laravel application.

## Features
- Create and manage checkout sessions
- Handle customer information and payment methods
- Generate payment URLs for redirecting users to the Thawani payment page
- Process payments through Thawani's API

## Installation
### 1. Require the Package

Use Composer to add the package to your Laravel project:

``` bash 
composer require Ialtoobi/Thawani-Manager
```

or clone the package using GIT:

git clone https://github.com/ialtoobi/Thawani-Manager.git
or Download the archive by [clicking here](https://github.com/ialtoobi/Thawani-Manager/archive/master.zip).

### 2. Publish Configuration 

Publish the package configuration to customize settings like (Mode, Secret Key, Publishable Key):
```bash
php artisan vendor:publish --tag=config
```
### 3. Configure Environment Variables

To configure your application to work with Thawani Pay, you need to set the following variables in your **.env file**. These settings will specify whether your application is in testing or production mode, provide the necessary API keys for production environment.

- THAWANI_MODE: Set this to either 'test' or 'live' based on your environment. This determines which set of API keys and endpoints the ThawaniManager will use.

- THAWANI_LIVE_SECRET_KEY: This is your live secret key provided by Thawani Pay. It is used to authenticate server-side API requests to Thawani.

- THAWANI_LIVE_PUBLISHABLE_KEY=: This is your live publishable key provided by Thawani Pay. It is used to authenticate client-side requests.

```php
THAWANI_MODE=test

THAWANI_LIVE_SECRET_KEY=your_live_secret_key

THAWANI_LIVE_PUBLISHABLE_KEY=your_live_publishable_key
```

## Basic Usage
### Creating a Checkout Session To Generate Payment URL
To create a new checkout session and redirect the user to the Thawani payment page:

```php
use Ialtoobi\Thawani\ThawaniManager;
use Illuminate\Support\Facades\Session;

$thawani = new ThawaniManager();

$data = [ 
    "client_reference_id" => "123412",
    "mode" => "payment",
    "products" => [
        [
            "name" => "product 1",
            "quantity" => 1,
            "unit_amount" => 100,
        ],
    ],
    "success_url" => "https://company.com/success",
    "cancel_url" => "https://company.com/cancel",
    "metadata" => [
        "Customer name" => "ialtoobi",
        "order id" => 0,
    ],
];

$create_checkout_session_result = $thawani->createCheckoutSession($data);

if ($create_checkout_session_result) {

    $session_id = $create_checkout_session_result['session_id'];
    $redirect_url = $create_checkout_session_result['redirect_url'];

    // Save session_id for later use, such as confirming payment status
    Session::put('session_id', $session_id);

    return redirect($redirect_url);
}else {
    // Handle errors or unsuccessful attempts here, such as logging the error or notifying the user
}
```

### Retrieving a Checkout Session
To retrieve details of an existing checkout session use `session_id`:
```php
// Assume $session_id is retrieved from a previous step or stored session
$session_id = 'your_session_id_here';

$retrieve_checkout_session_result  = $thawani->retrieveCheckoutSession($session_id);

$payment_status = $retrieve_checkout_session_result['data']['status'];

//Check payment status
if ($payment_status === 'paid') {
    // Handle successful payment
    // This could include updating order status in your database
}
```

### Managing Customers:

Create a customer to save card for later payment:

Make sure you provide created `customer_id` it in create checkout session.
```php
// Use a unique string to reference your customer 
$customer_data = [
    "client_customer_id": "customer_email@mail.com"
];

$create_customer_result = $thawani->createCustomer($customer_data);

//Save `customer_id` in the database to use for payment intent later
$customer_id = $create_customer_result['data']['id'];
```

To retrieve a customer use `customer_id`:
```php
$customer_id = 'your_customer_id_here';

$retrieve_customer_result = $thawani->retrieveCustomer($customer_id);

$data = $retrieve_customer_result['data'];
```

To delete a customer use `customer_id`:
```php
$customer_id = 'your_customer_id_here';

$delete_customer_result = $thawani->deleteCustomer($customer_id);

$description = $delete_customer_result['description'];
```

### Handling Payments:

To get list of saved payment methods related to customer using `customer_id`:
```php
$customer_id = 'your_customer_id_here';

$customer_payment_method_result = $thawani->customerPaymentMethod($customer_id);

//Example: select first `payment_method_id` to create payment intent later
$payment_method_id = $customer_payment_method_result['data'][0]['id'];
```

To delete a payment method use `card_id`:
```php
$card_id = 'card_id';

$payment_method_result = $thawani->deletePaymentMethod($card_id);

$description = $payment_method_result['description'];
```

### Payment Intent 
To create a payment intent:
```php
$paymentData = [
    "payment_method_id" => $payment_method_id, 
    "amount" => 100,
    "client_reference_id" => 1234,
    "return_url" => "https://company.com/success",
    "metadata" => [
        "customer_name" => 'Mohammed Al-Toubi',
        "order_id" => 1
    ]
];

$payment_intent_id = $thawani->createPaymentIntent($paymentData); 
```

To confirm a payment intent:
```php
$payment_intent_id = 'your_payment_intent_id_here';

$confirm_payment_intent_result = $thawani->confirmPaymentIntent($payment_intent_id);

if ($confirm_payment_intent_result) {
    
    $payment_id = $confirm_payment_intent_result['payment_id'];
    $redirect_url = $confirm_payment_intent_result['redirect_url'];

    // Save payment_id for later use, such as confirming payment status
    Session::put('payment_id', $payment_id);

    return redirect($redirect_url);
}else {
    // Handle errors or unsuccessful attempts here, such as logging the error or notifying the user
}
```
To retrieve details of payment intent use `payment_intent_id`:
```php
// Assume $payment_id is retrieved from a previous step or stored session
$payment_id = 'your_payment_id_here';

$retrieve_payment_intent_result  = $thawani->retrievePaymentIntent($payment_id);

$payment_status = $retrieve_payment_intent_result['data']['status'];

//Check payment status
if ($payment_status === 'succeeded') {
    // Handle successful payment
    // This could include updating order status in your database
}
```

To cancel paymant intent use `payment_intent_id`:
```php
$payment_intent_id = 'your_payment_intent_id_here';

$cancel_payment_intent_result = $thawani->cancelPaymentIntent($payment_intent_id);

$description = $cancel_payment_intent_result['description'];
```

## Support
For issues, questions, or contributions, please open an issue on the GitHub repository: [clicking here](https://github.com/ialtoobi/Thawani-Manager)

#### Follow me on X [@ialtoobi](https://x.com/ialtoobi/)

## License
Thawani Manager is open-sourced package licensed under the MIT license.


