# Some custom extensions for Filament Tiptap

This package provides extensions for the `awcodes/filament-tiptap-editor` package to integrate it with our own packages.

## Installation

You can install the package via composer:

```bash
composer require codedor/filament-custom-tiptap-extensions
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-custom-tiptap-extensions-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-custom-tiptap-extensions-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-custom-tiptap-extensions-views"
```

## Usage

```php
$filamentCustomTiptapExtensions = new Codedor\FilamentCustomTiptapExtensions();
echo $filamentCustomTiptapExtensions->echoPhrase('Hello, Codedor!');
```

## Documentation

For the full documentation, check [here](./docs/index.md).

## Testing

```bash
vendor/bin/pest
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Upgrading

Please see [UPGRADING](UPGRADING.md) for more information on how to upgrade to a new version.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover any security-related issues, please email info@codedor.be instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
