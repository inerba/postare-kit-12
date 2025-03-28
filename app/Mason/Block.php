<?php

namespace App\Mason;

use App\Filament\Actions\Forms\HtmlCleanAction;
use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\HtmlString;

class Block
{
    public static function make(): Brick
    {
        return Brick::make('block')
            ->label('Blocco di testo')
            ->modalHeading('Impostazioni del blocco di testo')
            ->icon(new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 256 256"><path d="M128,96H232a8,8,0,0,1,0,16H128a8,8,0,0,1,0-16Zm104,32H128a8,8,0,0,0,0,16H232a8,8,0,0,0,0-16Zm0,32H80a8,8,0,0,0,0,16H232a8,8,0,0,0,0-16Zm0,32H80a8,8,0,0,0,0,16H232a8,8,0,0,0,0-16ZM96,144a8,8,0,0,0,0-16H88V64h32v8a8,8,0,0,0,16,0V56a8,8,0,0,0-8-8H32a8,8,0,0,0-8,8V72a8,8,0,0,0,16,0V64H72v64H64a8,8,0,0,0,0,16Z"></path></svg>'))
            ->slideOver()
            ->fillForm(fn(array $arguments): array => array_merge(
                Macro\Theme::getArguments($arguments),
                Macro\SectionHeader::getArguments($arguments),
                Macro\ButtonsRepeater::getArguments($arguments),
                [
                    'content' => $arguments['content'] ?? null,
                ],
            ))
            ->form([
                Macro\SectionHeader::getFields(),
                TiptapEditor::make('content')
                    ->label('Contenuto')
                    ->placeholder('Content')
                    ->hintAction(HtmlCleanAction::make())
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
                                'identifier' => 'block',
                                'values' => $data,
                                'path' => 'mason.block',
                                'view' => view('mason.block', $data)->toHtml(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
