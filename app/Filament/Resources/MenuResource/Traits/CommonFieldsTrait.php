<?php

namespace App\Filament\Resources\MenuResource\Traits;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;

trait CommonFieldsTrait
{
    /**
     * Campi comuni per i link.
     *
     * @return array<int, Component>
     */
    public static function commonLinkFields(): array
    {
        return [
            Select::make('target')
                ->label('Target')
                ->options([
                    '' => 'Predefinito',
                    '_blank' => 'Nuova Finestra',
                    '_parent' => 'Genitore',
                    '_top' => 'Inizio',
                ]),
            CheckboxList::make('rel')
                ->columnSpan(1)
                ->columns(3)
                ->options([
                    'nofollow' => 'No follow',
                    'noopener' => 'No opener',
                    'noreferrer' => 'No referrer',
                ]),
        ];
    }
}
