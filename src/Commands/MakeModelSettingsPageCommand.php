<?php

namespace Quadrubo\FilamentModelSettings\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;

class MakeModelSettingsPageCommand extends Command
{
    use CanManipulateFiles;

    protected $description = 'Create a new Filament model settings page class';

    protected $signature = 'make:filament-model-settings-page {name?}';

    public function handle(): int
    {
        $page = (string) str($this->argument('name') ?? text(
            label: 'What is the page name?',
            placeholder: 'ManagePreferences',
            required: true,
        ))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        $pageClass = (string) str($page)->afterLast('\\');
        $pageNamespace = str($page)->contains('\\') ?
            (string) str($page)->beforeLast('\\') :
            '';

        $path = app_path(
            (string) str($page)
                ->prepend('Filament\\Pages\\')
                ->replace('\\', '/')
                ->append('.php'),
        );

        if ($this->checkForCollision([$path])) {
            return static::INVALID;
        }

        $this->copyStubToApp('ModelSettingsPage', $path, [
            'class' => $pageClass,
            'namespace' => 'App\\Filament\\Pages' . ($pageNamespace !== '' ? "\\{$pageNamespace}" : ''),
        ]);

        $this->components->info("Filament settings page [{$path}] created successfully.");

        return static::SUCCESS;
    }
}
