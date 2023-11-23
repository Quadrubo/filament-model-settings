# Filament Model Settings Plugin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/quadrubo/filament-model-settings.svg?style=flat-square)](https://packagist.org/packages/quadrubo/filament-model-settings)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/quadrubo/filament-model-settings/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/quadrubo/filament-model-settings/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/quadrubo/filament-model-settings/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/quadrubo/filament-model-settings/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/quadrubo/filament-model-settings.svg?style=flat-square)](https://packagist.org/packages/quadrubo/filament-model-settings)

This package utilizes [glorand/laravel-model-settings](https://github.com/glorand/laravel-model-settings) to incorporate model-specific settings into Filament. For instance, you can implement individualized settings for each user in your Filament application.

## Installation

You can install the package via composer:

```bash
composer require quadrubo/filament-model-settings
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-model-settings-views"
```

## Usage

You should start by setting up your eloquent model. You should read the [Instructions](https://github.com/glorand/laravel-model-settings#update_models) of the `glorand/laravel-model-settings` to find out how to do this.

Then you can start by generating a settings page.

```bash
php artisan make:filament-model-settings-page ManagePreferences
```

In your new settings page class, generated in the `app/Filament/{Panel}/Pages` directory, you should fill the `getSettingsRecord()` function. For example, to make user specific settings, simply return the currently active user:

```php
public static function getSettingRecord()
{
    return auth()->user();
}
```

You should also edit the `form()` function to create the fields for your settings.
For example, if you have the setting `theme` you can do this:

```php
public function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('theme')
                ->options([
                    'dark' => 'Dark Mode',
                    'light' => 'Light Mode',
                    'high_contrast' => 'High Contrast',
                ]),
        ]);
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Quadrubo](https://github.com/Quadrubo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
