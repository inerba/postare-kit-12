<?php

namespace App\Mason;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;

class Reviews
{
    public static function make(): Brick
    {
        return Brick::make('reviews')
            ->label('Recensioni')
            ->modalHeading('Impostazioni Recensioni')
            ->icon(new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 256 256"><path d="M216,80H184V48a16,16,0,0,0-16-16H40A16,16,0,0,0,24,48V176a8,8,0,0,0,13,6.22L72,154V184a16,16,0,0,0,16,16h93.59L219,230.22a8,8,0,0,0,5,1.78,8,8,0,0,0,8-8V96A16,16,0,0,0,216,80ZM66.55,137.78,40,159.25V48H168v88H71.58A8,8,0,0,0,66.55,137.78ZM216,207.25l-26.55-21.47a8,8,0,0,0-5-1.78H88V152h80a16,16,0,0,0,16-16V96h32Z"></path></svg>'))
            ->slideOver()
            ->fillForm(fn(array $arguments): array => array_merge(
                Macro\Theme::getArguments($arguments),
                Macro\SectionHeader::getArguments($arguments),
                Macro\ButtonsRepeater::getArguments($arguments),
                [
                    'reviews' => $arguments['reviews'] ?? [],
                    'perPage' => $arguments['perPage'] ?? 3,
                    'carouselGap' => $arguments['carouselGap'] ?? '0.5rem',
                    'pagination' => $arguments['pagination'] ?? true,
                    'arrows' => $arguments['arrows'] ?? true,
                    'rewind' => $arguments['rewind'] ?? true,
                ],
            ))
            ->form([
                Macro\SectionHeader::getFields(),
                Repeater::make('reviews')
                    ->schema([
                        Textarea::make('content')
                            ->label('Recensione')
                            ->required(),
                        TextInput::make('author')
                            ->label('Autore')
                            ->required(),
                    ]),

                Fieldset::make('Opzioni Carosello')
                    ->columns(2)
                    ->schema([
                        Select::make('perPage')
                            ->label('Elementi per pagina')
                            ->required()
                            ->native(false)
                            ->default(3)
                            ->options([
                                1 => '1',
                                2 => '2',
                                3 => '3',
                                4 => '4',
                                5 => '5',
                                6 => '6',
                                7 => '7',
                                8 => '8',
                                9 => '9',
                                10 => '10',
                                11 => '11',
                                12 => '12',
                            ]),

                        Select::make('carouselGap')
                            ->label('Spazio tra gli Elementi')
                            ->required()
                            ->native(false)
                            ->default('0.5rem')
                            ->options([
                                '0' => 'Nessuno',
                                '0.5rem' => '8px',
                                '1rem' => '16px',
                                '2rem' => '32px',
                                '3rem' => '48px',
                                '4rem' => '64px',
                            ]),

                        Toggle::make('pagination')
                            ->label('Paginazione')
                            ->helperText('Mostra i puntini di navigazione in basso')
                            ->visible(fn(Get $get) => $get('layout') === 'carousel')
                            ->default(true),

                        Toggle::make('arrows')
                            ->label('Frecce')
                            ->helperText('Mostra le frecce di navigazione ai lati')
                            ->visible(fn(Get $get) => $get('layout') === 'carousel')
                            ->default(true),

                        Toggle::make('rewind')
                            ->label('Riavvolgi')
                            ->helperText("Riavvolgi all'inizio dopo l'ultimo elemento")
                            ->visible(fn(Get $get) => $get('layout') === 'carousel')
                            ->default(true),
                    ]),
                Macro\ButtonsRepeater::getFields(),
                Macro\Theme::getFields(),
            ])
            ->action(function (array $arguments, array $data, Mason $component) {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setBrick',
                            arguments: [[
                                'identifier' => 'reviews',
                                'values' => $data,
                                'path' => 'mason.reviews',
                                'view' => view('mason.reviews', $data)->toHtml(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
