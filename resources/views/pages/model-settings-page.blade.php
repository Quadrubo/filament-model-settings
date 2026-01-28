<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        <div>
            {{ $this->form }}
        </div>

        <x-filament::actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"/>

    </form>
</x-filament-panels::page>
