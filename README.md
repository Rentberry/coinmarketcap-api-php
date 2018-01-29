Coinmarketcap.com API Client
---
PHP client for [CoinMarketCap JSON API](https://coinmarketcap.com/api/) 

# Installation
```
composer require rentberry/coinmarketcap-api
```

# Usage
```
$client = new Rentberry\Coinmarketcap\Conimarketcap();
$client->getTickers();
$client->getTicker('bitcoin');
$client->getExcahngeRate('ethereim', 'USD');
$client->convertToFiat(10, 'ethereum', 'USD');
$client->convertToCrypto(10, 'USD', 'ethereum');
$client->getGlobals();
```

# License
See [LICENSE](https://github.com/rentberry/coinmarketcap-api-php/blob/master/LICENSE)
