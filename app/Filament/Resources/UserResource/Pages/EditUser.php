<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use STS\FilamentImpersonate\Pages\Actions\Impersonate;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Impersonate::make()->record($this->getRecord()),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = $this->getRecord();
        $currentUser = Auth::user();

        if ($user->isSuperAdmin() && ! $currentUser->isSuperAdmin()) {
            Notification::make()
                ->title('Non autorizzato')
                ->body('Non hai i permessi necessari per modificare questo utente.')
                ->danger()
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));
        }

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->getRecord();
        $currentUser = Auth::user();

        if ($user->isSuperAdmin() && ! $currentUser->isSuperAdmin()) {
            Notification::make()
                ->title('Non autorizzato')
                ->body('Non hai i permessi necessari per visualizzare questo utente.')
                ->danger()
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));
        }

        return $data;
    }
}
