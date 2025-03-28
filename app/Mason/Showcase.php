<?php

namespace App\Mason;

use App\Enums\OfferType;
use App\Models\Car;
use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class Showcase
{
    public static function make(): Brick
    {
        return Brick::make('showcase')
            ->label('Vetrina annunci')
            ->modalHeading('Impostazioni vetrina annunci')
            ->icon(new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 256 256"><path d="M192,48H64A16,16,0,0,0,48,64V192a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64A16,16,0,0,0,192,48Zm0,144H64V64H192V192ZM240,56V200a8,8,0,0,1-16,0V56a8,8,0,0,1,16,0ZM32,56V200a8,8,0,0,1-16,0V56a8,8,0,0,1,16,0Z"></path></svg>'))
            ->slideOver()
            ->fillForm(fn (array $arguments): array => array_merge(
                Macro\Theme::getArguments($arguments),
                Macro\SectionHeader::getArguments($arguments),
                Macro\ButtonsRepeater::getArguments($arguments),
                [
                    'showcase' => $arguments['showcase'] ?? null,
                    'perPage' => $arguments['perPage'] ?? 3,
                    'pagination' => $arguments['pagination'] ?? false,
                    'arrows' => $arguments['arrows'] ?? true,
                    'rewind' => $arguments['rewind'] ?? true,
                    'carouselGap' => $arguments['carouselGap'] ?? '0.8rem',
                ],
            ))
            ->form([
                Macro\SectionHeader::getFields(),
                Section::make('Impostazioni carosello')
                    ->description('Modifica l\'aspetto del carosello')
                    ->collapsible()
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextInput::make('perPage')
                            ->label('Elementi per pagina')
                            ->numeric()
                            ->default(3)
                            ->required(),
                        TextInput::make('carouselGap')
                            ->label('Spazio tra le slide')
                            ->default('0.8rem')
                            ->required(),
                        Toggle::make('pagination')
                            ->label('Mostra paginazione')
                            ->default(false),
                        Toggle::make('arrows')
                            ->label('Mostra frecce')
                            ->default(true),
                        Toggle::make('rewind')
                            ->label('Riavvolgi carosello')
                            ->default(true),
                    ])
                    ->columnSpanFull(),
                Repeater::make('showcase.cars')
                    ->label('Auto')
                    ->schema([
                        Select::make('car_id')
                            ->label(false)
                            ->live(true)
                            ->searchable()
                            ->preload()
                            ->getSearchResultsUsing(function (string $search, Model $record, Get $get) {
                                // Ottieni tutte le auto già selezionate nel repeater
                                $selectedCarIds = collect($get('../'))
                                    ->pluck('car_id')
                                    ->filter()
                                    ->toArray();

                                return Car::where('title', 'like', "%{$search}%")
                                    ->where('dealer_id', $record->id)
                                    ->isPublished()
                                    ->whereNotIn('id', $selectedCarIds)
                                    ->limit(50)
                                    ->get(['id', 'title', 'dealer_id', 'offer_type'])
                                    ->mapWithKeys(fn ($car) => [$car->id => self::carTitle($car->id, $car)])
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                return self::carTitle($value);
                            }),
                    ])
                    ->addActionLabel('Aggiungi auto')
                    ->itemLabel(fn (array $state): ?string => self::carTitle($state['car_id']) ?? null)
                    ->columns(1)
                    ->grid(3)
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull(),
                Macro\ButtonsRepeater::getFields(),
                Macro\Theme::getFields(),
            ])
            ->action(function (array $arguments, array $data, Mason $component) {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setBrick',
                            arguments: [[
                                'identifier' => 'showcase',
                                'values' => $data,
                                'path' => 'mason.showcase',
                                'view' => view('mason.showcase', $data)->toHtml(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }

    protected static function carTitle($id = false, $car = false): string
    {
        if (! $id) {
            return 'Seleziona un\'auto';
        }

        if (! $car) {
            $car = Car::find($id);
        }

        if (! $car) {
            return 'Auto non trovata';
        }

        $offerType = OfferType::options()[$car->offer_type] ?? 'Tipo offerta sconosciuta';

        return "{$car->title} / {$offerType} / id:{$car->id}";
    }
}
