<?php

namespace Quadrubo\FilamentModelSettings\Macros;

use Filament\Forms\Components\Field;
use Filament\Tables\Columns\TextColumn;

/**
 * @param  string  $prefix
 *
 * @mixin \Filament\Forms\Components\Field;
 *
 * @return \Filament\Forms\Components\Field;
 */
class IsModelSetting
{
    public function field()
    {
        return function (string $prefix = 'settings') {
            /** @var \Filament\Forms\Components\Field $this */
            $this->afterStateHydrated(function (Field $component, $state) use ($prefix) {
                $statePath = $component->getStatePath(false);

                if (str_starts_with($statePath, $prefix)) {
                    $statePath = substr($statePath, strlen($prefix) + 1);
                }

                if ($state === null && $component->getRecord() !== null) {
                    $record = $component->getRecord();

                    if (! method_exists($record, 'settings')) {
                        return;
                    }

                    $component->state($record->settings()->get($statePath));
                }
            });

            return $this;
        };
    }

    public function textColumn()
    {
        return function () {

            /** @var \Filament\Tables\Columns\TextColumn $this */
            // default is important, otherwise formatStateUsing will not be called since it's not a field
            $this->default('settings');
            $this->formatStateUsing(function ($column, $livewire, $record) {

                if (! method_exists($record, 'settings')) {
                    return '';
                }

                return $record->settings()->get($column->name);
            });

            return $this;
        };
    }

    public function textEntry()
    {
        return function () {
            /** @var \Filament\Infolists\Components\TextEntry $this */
            $this->getStateUsing(fn ($record) => $record->settings()->get($this->getStatePath()));

            return $this;
        };
    }
}
