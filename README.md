Coinmarketcap.com API Client
---
[![Build Status](https://img.shields.io/travis/Rentberry/coinmarketcap-api-php.svg?style=flat-square)](https://travis-ci.org/Rentberry/coinmarketcap-api-php)
[![License](https://img.shields.io/packagist/l/rentberry/coinmarketcap-api.svg?style=flat-square)](https://packagist.org/packages/rentberry/coinmarketcap-api)
[![Latest Stable Version](https://img.shields.io/packagist/v/rentberry/coinmarketcap-api.svg?style=flat-square)](https://packagist.org/packages/rentberry/coinmarketcap-api)
[![Total Downloads](https://img.shields.io/packagist/dt/rentberry/coinmarketcap-api.svg?style=flat-square)](https://packagist.org/packages/rentberry/coinmarketcap-api)

PHP client for [CoinMarketCap JSON API](https://coinmarketcap.com/api/) 

# Installation
```bash
composer require rentberry/coinmarketcap-api
```

# Usage
```php
$client = new Rentberry\Coinmarketcap\Conimarketcap();
$client->getTickers();
$client->getTicker('bitcoin');
$client->getExcahngeRate('ethereim', 'USD');
$client->convertToFiat(10, 'ethereum', 'USD');
$client->convertToCrypto(10, 'USD', 'ethereum');
$client->getGlobals();
```

# License
MIT. See [LICENSE](https://github.com/rentberry/coinmarketcap-api-php/blob/master/LICENSE)
