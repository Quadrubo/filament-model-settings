<?php

namespace Quadrubo\FilamentModelSettings;

use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Quadrubo\FilamentModelSettings\Commands\MakeModelSettingsPageCommand;
use Quadrubo\FilamentModelSettings\Testing\TestsFilamentModelSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentModelSettingsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-model-settings';

    public static string $viewNamespace = 'filament-model-settings';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands());

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageBooted(): void
    {
        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-model-settings/{$file->getFilename()}"),
                ], 'filament-model-settings-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentModelSettings());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'quadrubo/filament-model-settings';
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            MakeModelSettingsPageCommand::class,
        ];
    }
}
