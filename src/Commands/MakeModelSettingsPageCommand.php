<?php

namespace Quadrubo\FilamentModelSettings\Commands;

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class MakeModelSettingsPageCommand extends Command
{
    use CanManipulateFiles;

    protected $description = 'Create a new Filament model settings page class';

    protected $signature = 'make:filament-model-settings-page {name?} {--panel=} {--F|force}';

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

        $panel = $this->option('panel');

        if ($panel) {
            $panel = Filament::getPanel($panel);
        }

        if (! $panel) {
            $panels = Filament::getPanels();

            /** @var Panel $panel */
            $panel = (count($panels) > 1) ? $panels[select(
                label: 'Which panel would you like to create this in?',
                options: array_map(
                    fn (Panel $panel): string => $panel->getId(),
                    $panels,
                ),
                default: Filament::getDefaultPanel()->getId()
            )] : Arr::first($panels);
        }

        $pageDirectories = $panel->getPageDirectories();
        $pageNamespaces = $panel->getPageNamespaces();

        $namespace = (count($pageNamespaces) > 1) ?
                select(
                    label: 'Which namespace would you like to create this in?',
                    options: $pageNamespaces
                ) :
                (Arr::first($pageNamespaces) ?? 'App\\Filament\\Pages');
        $path = (count($pageDirectories) > 1) ?
            $pageDirectories[array_search($namespace, $pageNamespaces)] :
            (Arr::first($pageDirectories) ?? app_path('Filament/Pages/'));

        $path = (string) str($page)
            ->prepend('/')
            ->prepend(($path ?? ''))
            ->replace('\\', '/')
            ->replace('//', '/')
            ->append('.php');

        if (! $this->option('force') && $this->checkForCollision([$path])) {
            return static::INVALID;
        }

        $this->copyStubToApp('ModelSettingsPage', $path, [
            'class' => $pageClass,
            'namespace' => str($namespace ?? '') . ($pageNamespace !== '' ? "\\{$pageNamespace}" : ''),
        ]);

        $this->components->info("Filament model settings page [{$path}] created successfully.");

        return static::SUCCESS;
    }
}
