## Thawani-Manager

A Laravel package for integrating Thawani payment gateway.

## How to install
You can use composer:

```composer require Ialtoobi/Thawani-Manager```

or clone the class using GIT:

    git clone https://github.com/ialtoobi/Thawani-Manager.git
or Download the archive by [clicking here](https://github.com/ialtoobi/Thawani-Manager/archive/master.zip).

## Usage
### In .env file Initialize these configuration to specify environment (Testing || Production):
```php

# Thawani Pay Configuration for Testing Environment
THAWANI_TEST_BASE_URL=https://uatcheckout.thawani.om/api/v1
THAWANI_CHECKOUT_TEST_URL=https://uatcheckout.thawani.om/pay
THAWANI_TEST_SECRET_KEY=rRQ26GcsZzoEhbrP2HZvLYDbn9C9et
THAWANI_TEST_PUBLISHABLE_KEY=HGvTMLDssJghr9tlN9gr4DVYt0qyBy


# Thawani Pay Configuration for Production Environment
THAWANI_LIVE_BASE_URL=https://checkout.thawani.om/api/v1
THAWANI_CHECKOUT_PROD_URL=https://checkout.thawani.om/pay
THAWANI_LIVE_SECRET_KEY=your_live_secret_key
THAWANI_LIVE_PUBLISHABLE_KEY=your_live_publishable_key

```
### In config/services.php :
```php
'thawani' => [
    'test' => [
        'base_url' => env('THAWANI_TEST_BASE_URL'),
        'checkout_base_url' => env('THAWANI_CHECKOUT_TEST_URL'),
        'secret_key' => env('THAWANI_TEST_SECRET_KEY'),
        'publishable_key' => env('THAWANI_TEST_PUBLISHABLE_KEY'),
    ],
    'live' => [
        'base_url' => env('THAWANI_LIVE_BASE_URL'),
        'checkout_base_url' => env('THAWANI_CHECKOUT_PROD_URL'),
        'secret_key' => env('THAWANI_LIVE_SECRET_KEY'),
        'publishable_key' => env('THAWANI_LIVE_PUBLISHABLE_KEY'),
    ],
],
```
## Basic Usage


### Use ThawaniManager class :
```php
    use Ialtoobi\Thawani\ThawaniManager;

    $thawaniManager = new ThawaniManager();
```

### Create Checkout Session To Generate Payment URL:
```php

```

### Check Payment Status:
```php

```
