<?php

namespace Quadrubo\FilamentModelSettings\Macros;

use Filament\Forms\Components\Field;

/**
 * @param  string  $prefix
 *
 * @mixin \Filament\Forms\Components\Field;
 *
 * @return \Filament\Forms\Components\Field;
 */
class IsModelSetting
{
    public function __invoke()
    {
        return function (string $prefix = 'settings') {
            /** @var \Filament\Forms\Components\Field $this */
            $this->afterStateHydrated(function (Field $component, $state) use ($prefix) {
                $statePath = $component->getStatePath(false);

                if (str_starts_with($statePath, $prefix)) {
                    $statePath = substr($statePath, strlen($prefix) + 1);
                }

                if ($state === null && $component->getRecord() !== null) {
                    $component->state($component->getRecord()->settings()->get($statePath));
                }
            });

            return $this;
        };
    }
}
