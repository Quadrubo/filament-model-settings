<?php

namespace Quadrubo\FilamentModelSettings\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Glorand\Model\Settings\Contracts\SettingsManagerContract;
use Quadrubo\FilamentModelSettings\Exceptions\HasModelSettingsNotImplementedException;
use Quadrubo\FilamentModelSettings\Pages\Contracts\HasModelSettings;

class ModelSettingsPage extends Page implements HasForms
{
    use InteractsWithFormActions;

    protected string $view = 'filament-model-settings::pages.model-settings-page';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        if (! $this instanceof HasModelSettings) {
            throw new HasModelSettingsNotImplementedException;
        }

        $this->callHook('beforeFill');

        $settings = $this->getSettingRecord()->settings();

        $data = $this->mutateFormDataBeforeFill($settings->all());

        /** @phpstan-ignore-next-line */
        $this->form->fill(blank($data) ? null : $data);

        $this->callHook('afterFill');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    public function save(): void
    {
        if (! $this instanceof HasModelSettings) {
            throw new HasModelSettingsNotImplementedException;
        }

        try {
            $this->callHook('beforeValidate');

            /** @phpstan-ignore-next-line */
            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->handleRecordUpdate($this->getSettingRecord()->settings(), $data);

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }

        $this->getSavedNotification()?->send();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl);
        }
    }

    public function getSavedNotification(): ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($title);
    }

    public function getSavedNotificationTitle(): ?string
    {
        return __('filament-model-settings::model-settings.notifications.saved.title');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(SettingsManagerContract $settings, array $data): SettingsManagerContract
    {
        return $settings->apply((array) $data);
    }

    /**
     * @return array<Action | ActionGroup>
     */
    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-model-settings::model-settings.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(2)
            ->inlineLabel($this->hasInlineLabels());
    }

    public function getRedirectUrl(): ?string
    {
        return null;
    }
}
