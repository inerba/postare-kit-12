<?php

namespace App\Mason;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\HtmlString;

class Image
{
    public static function make(): Brick
    {
        return Brick::make('image')
            ->label('Immagine')
            ->modalHeading('Impostazioni immagine')
            ->icon(new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,16V158.75l-26.07-26.06a16,16,0,0,0-22.63,0l-20,20-44-44a16,16,0,0,0-22.62,0L40,149.37V56ZM40,172l52-52,80,80H40Zm176,28H194.63l-36-36,20-20L216,181.38V200ZM144,100a12,12,0,1,1,12,12A12,12,0,0,1,144,100Z"></path></svg>'))
            ->slideOver()
            ->fillForm(fn (array $arguments): array => array_merge(
                Macro\Theme::getArguments($arguments),
                Macro\SectionHeader::getArguments($arguments),
                Macro\ButtonsRepeater::getArguments($arguments),
                [
                    'image' => $arguments['image'] ?? null,
                ],
            ))
            ->form([
                Macro\SectionHeader::getFields(),
                FileUpload::make('image')
                    ->label('Immagine')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        null,
                    ])
                    ->maxSize(10480)
                    ->required()
                    ->downloadable()
                    ->directory('mason_images'),
                Macro\ButtonsRepeater::getFields(),
                Macro\Theme::getFields(),
            ])
            ->action(function (array $arguments, array $data, Mason $component) {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setBrick',
                            arguments: [[
                                'identifier' => 'image',
                                'values' => $data,
                                'path' => 'mason.image',
                                'view' => view('mason.image', $data)->toHtml(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
