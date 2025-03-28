<?php

namespace App\Mason\Macro;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\HtmlString;

class ButtonsRepeater
{
    public static function getProps(): array
    {
        return [
            'buttons' => [],
        ];
    }

    public static function getArguments($arguments): array
    {
        return [
            'buttons' => $arguments['buttons'] ?? [],
        ];
    }

    public static function getFields()
    {
        return Repeater::make('buttons')
            ->label('Pulsanti')
            ->hint('Aggiungi uno o più pulsanti')
            ->columns(2)
            ->minItems(0)
            ->defaultItems(0)
            ->schema([
                TextInput::make('button_text')
                    ->live(true)
                    ->label('Testo del pulsante')
                    ->required(),
                Select::make('button_target')
                    ->label('Target del link')
                    ->options([
                        '_self' => 'Stessa finestra',
                        '_blank' => 'Nuova finestra',
                    ])
                    ->required(),
                TextInput::make('button_link')
                    ->label('Link del pulsante')
                    ->columnSpanFull()
                    ->required(),
                Radio::make('class')
                    ->label('Colore del pulsante')
                    ->columnSpanFull()
                    ->options([
                        'bg-transparent border-2 border-black text-black hover:bg-gray-100' => new HtmlString('<div class="flex flex-col items-center gap-2 text-sm cursor-pointer"><div class="w-12 h-12 bg-transparent border-2 border-black">&nbsp;</div>Bordo</div>'),
                        'bg-gray-100 text-black hover:bg-gray-200' => new HtmlString('<div class="flex flex-col items-center gap-2 text-sm cursor-pointer"><div class="w-12 h-12 bg-gray-100 border">&nbsp;</div>Grigio</div>'),
                        'bg-red-700 text-white hover:bg-red-800' => new HtmlString('<div class="flex flex-col items-center gap-2 text-sm cursor-pointer"><div class="w-12 h-12 bg-red-700">&nbsp;</div>Rosso</div>'),
                        'bg-green-700 text-white hover:bg-green-800' => new HtmlString('<div class="flex flex-col items-center gap-2 text-sm cursor-pointer"><div class="w-12 h-12 bg-green-700">&nbsp;</div>Verde</div>'),
                    ])
                    ->inline()
                    ->inlineLabel(false),
            ])
            ->collapsed()
            ->cloneable()
            ->grid(2)
            ->addActionLabel('Aggiungi pulsante')
            ->itemLabel(fn (array $state): ?string => $state['button_text'] ?? null);
    }
}
