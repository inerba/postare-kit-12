<?php

namespace App\Mason;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Accordion
{
    public static function make(): Brick
    {
        return Brick::make('accordion')
            ->label('Accordion')
            ->modalHeading('Impostazioni accordion')
            ->icon(new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 256 256"><path d="M216,80H184V48a16,16,0,0,0-16-16H40A16,16,0,0,0,24,48V176a8,8,0,0,0,13,6.22L72,154V184a16,16,0,0,0,16,16h93.59L219,230.22a8,8,0,0,0,5,1.78,8,8,0,0,0,8-8V96A16,16,0,0,0,216,80ZM66.55,137.78,40,159.25V48H168v88H71.58A8,8,0,0,0,66.55,137.78ZM216,207.25l-26.55-21.47a8,8,0,0,0-5-1.78H88V152h80a16,16,0,0,0,16-16V96h32Z"></path></svg>'))
            ->slideOver()
            ->fillForm(fn(array $arguments): array => array_merge(
                Macro\Theme::getArguments($arguments),
                Macro\SectionHeader::getArguments($arguments),
                Macro\ButtonsRepeater::getArguments($arguments),
                [
                    'accordion' => $arguments['accordion'] ?? null,
                ],
            ))
            ->form([
                Macro\SectionHeader::getFields(),
                Repeater::make('accordion')
                    ->minItems(1)
                    ->defaultItems(1)
                    ->schema([
                        Textarea::make('question')
                            ->live(true)
                            ->label('Domanda')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('answer')
                            ->label('Risposta')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->addActionLabel('Aggiungi elemento')
                    ->itemLabel(fn(array $state): ?string => $state['question'] ? Str::limit($state['question'], 50) : null)
                    ->columns(2),
                Macro\ButtonsRepeater::getFields(),
                Macro\Theme::getFields(),
            ])
            ->action(function (array $arguments, array $data, Mason $component) {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setBrick',
                            arguments: [[
                                'identifier' => 'accordion',
                                'values' => $data,
                                'path' => 'mason.accordion',
                                'view' => view('mason.accordion', $data)->toHtml(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
