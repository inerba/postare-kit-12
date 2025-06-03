<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomExceptionResource\Pages;
use BezhanSalleh\FilamentExceptions\Resources\ExceptionResource;
use BezhanSalleh\FilamentExceptions\Resources\ExceptionResource\Pages\ViewException;

class CustomExceptionResource extends ExceptionResource
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Errori';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getSlug(): string
    {
        return 'errors';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomExceptions::route('/'),
            'view' => ViewException::route('/{record}'),
        ];
    }
}
