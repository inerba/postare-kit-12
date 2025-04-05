<?php

namespace App\Mason;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Creagia\FilamentCodeField\CodeField;
use Illuminate\Support\HtmlString;

class Code
{
    public static function make(): Brick
    {
        return Brick::make('code')
            ->label('Codice')
            ->modalHeading('Impostazioni del codice')
            ->icon(new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 256 256"><path d="M69.12,94.15,28.5,128l40.62,33.85a8,8,0,1,1-10.24,12.29l-48-40a8,8,0,0,1,0-12.29l48-40a8,8,0,0,1,10.24,12.3Zm176,27.7-48-40a8,8,0,1,0-10.24,12.3L227.5,128l-40.62,33.85a8,8,0,1,0,10.24,12.29l48-40a8,8,0,0,0,0-12.29ZM162.73,32.48a8,8,0,0,0-10.25,4.79l-64,176a8,8,0,0,0,4.79,10.26A8.14,8.14,0,0,0,96,224a8,8,0,0,0,7.52-5.27l64-176A8,8,0,0,0,162.73,32.48Z"></path></svg>'))
            ->slideOver()
            ->fillForm(fn (array $arguments): array => array_merge(
                Macro\Theme::getArguments($arguments),
                Macro\SectionHeader::getArguments($arguments),
                Macro\ButtonsRepeater::getArguments($arguments),
                [
                    'code' => $arguments['code'] ?? null,
                ],
            ))
            ->form([
                Macro\SectionHeader::getFields(),
                CodeField::make('code')
                    ->htmlField()
                    ->label('Codice')
                    ->withLineNumbers()
                    ->required(),
                Macro\ButtonsRepeater::getFields(),
                Macro\Theme::getFields(),
            ])
            ->action(function (array $arguments, array $data, Mason $component) {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setBrick',
                            arguments: [[
                                'identifier' => 'code',
                                'values' => $data,
                                'path' => 'mason.code',
                                'view' => view('mason.code', $data)->toHtml(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
