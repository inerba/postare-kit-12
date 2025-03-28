<?php

namespace App\Mason\Macro;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Set;

class SectionHeader
{
    public static function getProps(): array
    {
        return [
            'header_title' => null,
            'header_align' => 'text-center',
            'header_tagline' => null,
        ];
    }

    public static function getArguments($arguments): array
    {
        return [
            'header_title' => $arguments['header_title'] ?? null,
            'header_align' => $arguments['header_title_align'] ?? 'center',
            'header_tagline' => $arguments['header_tagline'] ?? null,
        ];
    }

    public static function getFields(): Section
    {
        return Section::make('Header')
            ->collapsed()
            ->columns(4)
            ->schema([
                Textarea::make('header_title')
                    ->columnSpan(3)
                    ->label('Titolo'),
                Select::make('header_align')
                    ->label('Allineamento')
                    ->default('center')
                    ->options([
                        'left' => 'Sinistra',
                        'center' => 'Centro',
                        'right' => 'Destra',
                    ]),
                Textarea::make('header_tagline')
                    ->columnSpanFull()
                    ->label('Sottotitolo'),
                Actions::make([

                    Action::make('delete')
                        ->label('Cancella tutto')
                        ->icon('heroicon-m-trash')
                        ->size('sm')
                        ->requiresConfirmation()
                        ->action(function (Set $set, $state) {
                            $set('header_title', null);
                            $set('header_align', 'center');
                            $set('header_tagline', null);
                        }),
                ]),
            ]);
    }
}
