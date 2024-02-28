## Thawani-Manager

A Laravel package for integrating Thawani payment gateway.

## How to install
You can use composer:

```composer require Ialtoobi/Thawani-Manager```

or clone the class using GIT:

    git clone https://github.com/ialtoobi/Thawani-Manager.git
or Download the archive by [clicking here](https://github.com/ialtoobi/Thawani-Manager/archive/master.zip).

## Basic Usage
### In .env file Initialize these configuration to specify environment (Testing || Production):
```php

#Change mode based on environment 'test' || 'live' for production.
THAWANI_MODE=test

# Thawani Pay Configuration for Production Environment
THAWANI_LIVE_SECRET_KEY=your_live_secret_key
THAWANI_LIVE_PUBLISHABLE_KEY=your_live_publishable_key

```
<<<<<<< HEAD
=======


>>>>>>> 4e11703 (Update read me file)

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
