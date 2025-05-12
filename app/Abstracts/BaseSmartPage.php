<?php

namespace App\Abstracts;

use App\Models\SmartPage;
use Filament\Actions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Concerns\Translatable;

abstract class BaseSmartPage extends Page implements HasForms
{
    use InteractsWithForms;
    use Translatable;

    public ?array $data = [];

    public ?string $activeLocale = 'it';

    public function getActiveFormsLocale(): ?string
    {
        return $this->getTranslatableLocales()[0];
    }

    protected static ?string $navigationGroup = 'Smart Pages';

    // Metodo astratto per ottenere il nome delle impostazioni specifiche
    abstract protected function pageName(): string;

    public function mount(): void
    {
        $this->data = SmartPage::getPageValues($this->pageName());

        $this->form->fill($this->data);
    }

    public function save(): void
    {
        collect($this->data)->each(function ($value, $key) {
            SmartPage::set($this->pageName().'.'.$key, $value);
        });

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function addLocale($key): string
    {
        return $key.'.'.$this->activeLocale;
    }
}
