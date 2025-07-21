<?php

namespace App\Mason\Macro;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;

class Gallery
{
    /**
     * Restituisce le proprietà del componente
     *
     * @return array<string, mixed>
     */
    public static function getProps(): array
    {
        return ['theme' => []];
    }

    /**
     * Restituisce gli argomenti del componente
     *
     * @param  array<string, mixed>  $arguments
     * @return array<string, mixed>
     */
    public static function getArguments($arguments): array
    {
        return [
            'theme' => [
                'background_color' => $arguments['theme']['background_color'] ?? 'white',
                'blockMaxWidth' => $arguments['theme']['blockMaxWidth'] ?? 'max-w-3xl',
                'blockMaxWidthSm' => $arguments['theme']['blockMaxWidthSm'] ?? null,
                'blockMaxWidthMd' => $arguments['theme']['blockMaxWidthMd'] ?? null,
                'blockMaxWidthLg' => $arguments['theme']['blockMaxWidthLg'] ?? null,
                'blockMaxWidthXl' => $arguments['theme']['blockMaxWidthXl'] ?? null,
                'blockMaxWidth2Xl' => $arguments['theme']['blockMaxWidth2xl'] ?? null,
                'blockVerticalPadding' => $arguments['theme']['blockVerticalPadding'] ?? 'py-6',
                'blockVerticalPaddingSm' => $arguments['theme']['blockVerticalPaddingSm'] ?? null,
                'blockVerticalPaddingMd' => $arguments['theme']['blockVerticalPaddingMd'] ?? null,
                'blockVerticalPaddingLg' => $arguments['theme']['blockVerticalPaddingLg'] ?? 'lg:py-24',
                'blockVerticalPaddingXl' => $arguments['theme']['blockVerticalPaddingXl'] ?? null,
                'blockVerticalPadding2Xl' => $arguments['theme']['blockVerticalPadding2xl'] ?? null,
            ],
        ];
    }

    /**
     * Restituisce i campi del componente
     *
     * @return Tabs
     */
    public static function getFields()
    {
        return Tabs::make('Tabs')
            ->tabs([
                Tabs\Tab::make('Impostazioni galleria')
                    ->columns(2)
                    ->schema([
                        Select::make('layout')
                            ->label('Layout gallery')
                            ->hint('Seleziona il layout della galleria')
                            ->live()
                            ->required()
                            ->native(false)
                            ->default('grid')
                            ->options([
                                // Se aggiungi un layout, ricordati di modificare anche il metodo arrayOfColumns
                                'grid' => 'Griglia',
                                'carousel' => 'Carosello',
                                // 'masonry' => 'Masonry',
                            ]),

                        Select::make('thumbnail')
                            ->label('Miniatura')
                            ->visible(fn (Get $get) => $get('layout'))
                            ->default('thumbnail')
                            ->required()
                            ->native(false)
                            ->options([
                                'icon' => 'Icona 90x90',
                                'square' => 'Miniatura Quadrata',
                                'thumbnail' => 'Miniatura 16:9',
                                'lg' => 'Rapporto originale Grande',
                            ]),

                        Fieldset::make('Opzioni Carosello')
                            ->columns(2)
                            ->visible(fn (Get $get) => $get('layout') === 'carousel')
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
                                    ->visible(fn (Get $get) => $get('layout') === 'carousel')
                                    ->default(true),

                                Toggle::make('arrows')
                                    ->label('Frecce')
                                    ->helperText('Mostra le frecce di navigazione ai lati')
                                    ->visible(fn (Get $get) => $get('layout') === 'carousel')
                                    ->default(true),

                                Toggle::make('rewind')
                                    ->label('Riavvolgi')
                                    ->helperText("Riavvolgi all'inizio dopo l'ultimo elemento")
                                    ->visible(fn (Get $get) => $get('layout') === 'carousel')
                                    ->default(true),

                                //

                            ]),

                        Fieldset::make('Colonne')
                            // ->description('Configura le colonne dellla gallery per adattarsi a diverse dimensioni di schermo.')
                            ->columns(3)
                            ->visible(fn (Get $get) => $get('layout') && $get('layout') != 'carousel')
                            ->schema(
                                array_map(function ($size) {
                                    return Select::make('columns'.($size !== 'xs' ? ucfirst($size) : ''))
                                        ->label(Theme::responsiveLabel($size))
                                        ->placeholder($size === 'xs' ? 'Non indicato' : 'Non indicato / Ereditato')
                                        ->options(fn (Get $get) => self::arrayOfColumns($get('layout')))
                                        ->default(match ($size) {
                                            'xs' => 1,
                                            'sm' => 2,
                                            'md' => null,
                                            'lg' => 3,
                                            'xl' => null,
                                            '2xl' => null,
                                        });
                                }, ['xs', 'sm', 'md', 'lg', 'xl', '2xl'])
                            ),
                    ]),

            ]);
    }

    /**
     * Restituisce un array associativo di colonne basato sul layout specificato.
     *
     * @param  string  $layout  Il layout per cui ottenere il numero massimo di colonne.
     *                          Può essere 'grid', 'carousel' o 'masonry'.
     * @return array<int, int> Un array associativo dove le chiavi e i valori rappresentano il numero di colonne.
     */
    public static function arrayOfColumns(string $layout): array
    {
        // il numero indicato rappresenta il massimo numero di colonne impostabili
        $columns = [
            'grid' => 12,
            'carousel' => 4,
            'masonry' => 6,
        ];

        // Se il layout non è presente nell'array, si utilizza il layout 'grid' come default
        $maxColumns = $columns[$layout] ?? $columns['grid'];

        return array_combine(range(1, $maxColumns), range(1, $maxColumns));

        // For Tailwind CSS
        // grid-cols-1 grid-cols-2 grid-cols-3 grid-cols-4 grid-cols-5 grid-cols-6 grid-cols-7 grid-cols-8 grid-cols-9 grid-cols-10 grid-cols-11 grid-cols-12
        // sm:grid-cols-1 sm:grid-cols-2 sm:grid-cols-3 sm:grid-cols-4 sm:grid-cols-5 sm:grid-cols-6 sm:grid-cols-7 sm:grid-cols-8 sm:grid-cols-9 sm:grid-cols-10 sm:grid-cols-11 sm:grid-cols-12
        // md:grid-cols-1 md:grid-cols-2 md:grid-cols-3 md:grid-cols-4 md:grid-cols-5 md:grid-cols-6 md:grid-cols-7 md:grid-cols-8 md:grid-cols-9 md:grid-cols-10 md:grid-cols-11 md:grid-cols-12
        // lg:grid-cols-1 lg:grid-cols-2 lg:grid-cols-3 lg:grid-cols-4 lg:grid-cols-5 lg:grid-cols-6 lg:grid-cols-7 lg:grid-cols-8 lg:grid-cols-9 lg:grid-cols-10 lg:grid-cols-11 lg:grid-cols-12
        // xl:grid-cols-1 xl:grid-cols-2 xl:grid-cols-3 xl:grid-cols-4 xl:grid-cols-5 xl:grid-cols-6 xl:grid-cols-7 xl:grid-cols-8 xl:grid-cols-9 xl:grid-cols-10 xl:grid-cols-11 xl:grid-cols-12
        // 2xl:grid-cols-1 2xl:grid-cols-2 2xl:grid-cols-3 2xl:grid-cols-4 2xl:grid-cols-5 2xl:grid-cols-6 2xl:grid-cols-7 2xl:grid-cols-8 2xl:grid-cols-9 2xl:grid-cols-10 2xl:grid-cols-11 2xl:grid-cols-12
    }
}
