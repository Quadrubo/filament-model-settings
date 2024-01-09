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

You should start by setting up your eloquent model.  
**Important:** You should read the [Instructions](https://github.com/glorand/laravel-model-settings#update_models) of the `glorand/laravel-model-settings` to find out how to do this.

### Seperate Settings Page

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

#### Using the Page in the user menu

If you want to use this page in filaments user menu, you can create an entry in your panel provider.

```php
use App\Filament\Admin\Pages\ManagePreferences;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->
            ...
            ->userMenuItems([
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn (): string => ManagePreferences::getUrl())
                    ->icon('heroicon-o-cog-6-tooth'),
            ]);
    }
}
```

You may also want to hide the page in the sidebar.

```php
namespace App\Filament\Admin\Pages;

class ManagePreferences extends ModelSettingsPage implements HasModelSettings
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
```

### Settings within your existing Resouce

The settings can also be used in your existing resource.
If, for example you have a school model with the settings `color` and `can_add_students`.

```php
namespace App\Models;

use Glorand\Model\Settings\Traits\HasSettingsField;

class School extends Model
{
    use HasSettingsField;

    public $defaultSettings = [
        'color' => '#ff0000',
        'can_add_students' => true,
    ];
}
```

You can then use the provided macro `isModelSetting()` to use these settings inside your resource.

```php
namespace App\Filament\Resources;

class SchoolResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\ColorPicker::make('settings.color_1')
                    ->isModelSetting(),
                Forms\Components\Toggle::make('settings.can_add_students')
                    ->isModelSetting(),
            ]);
    }
}
```

In case you changed your column name for the settings, you should provide that to `isModelSetting` as a prefix.

```php
Forms\Components\Toggle::make('school_stuff.can_add_students')
    ->isModelSetting('school_stuff'),
```

## Testing

```bash
composer test
```

## Inspiration

This package is heavily inspired by the official [Spatie Laravel Settings Plugin](https://github.com/filamentphp/spatie-laravel-settings-plugin) for filament and basically just implements a few changes to make it compatible with  [glorand/laravel-model-settings](https://github.com/glorand/laravel-model-settings).

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
