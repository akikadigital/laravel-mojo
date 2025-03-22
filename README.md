# Laravel Mojo

[![Latest Version on Packagist](https://img.shields.io/packagist/v/akika/laravel-mojo.svg?style=flat-square)](https://packagist.org/packages/akika/laravel-mojo)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/akikadigital/laravel-mojo/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/akikadigital/laravel-mojo/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/akikadigital/laravel-mojo/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/akikadigital/laravel-mojo/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/akika/laravel-mojo.svg?style=flat-square)](https://packagist.org/packages/akika/laravel-mojo)

An unofficial laravel package for interfacing with the mojo API.

## Installation

You can install the package via composer:

```bash
composer require akika/laravel-mojo
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-mojo-config"
```

## Usage

```php
use Akika\Mojo\Enums\Currency;
use Akika\Mojo\Http\Integrations\CashOutClient;

$cashOutClient = Akika\Mojo::cashOutClient();
echo $cashOutClient->getBankList(Currency::GHS);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Timothy Karani](https://github.com/akika)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
