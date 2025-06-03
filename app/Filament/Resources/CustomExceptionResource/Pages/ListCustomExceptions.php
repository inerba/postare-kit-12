<?php

namespace App\Filament\Resources\CustomExceptionResource\Pages;

use App\Filament\Resources\CustomExceptionResource;
use BezhanSalleh\FilamentExceptions\Models\Exception;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListCustomExceptions extends ListRecords
{
    protected static string $resource = CustomExceptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('svuota')
                ->label('Svuota Tabella')
                ->visible(Exception::query()->exists())
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        Exception::truncate();
                        Notification::make()
                            ->title('Tabella svuotata con successo')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Errore durante lo svuotamento della tabella')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
