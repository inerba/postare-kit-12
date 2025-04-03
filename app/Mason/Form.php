<?php

namespace App\Mason;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use Nette\Utils\Html;

class Form
{
    public static function make(): Brick
    {
        return Brick::make('form')
            ->label('Form')
            ->modalHeading('Form Settings')
            ->icon(new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 256 256"><path d="M227.32,28.68a16,16,0,0,0-15.66-4.08l-.15,0L19.57,82.84a16,16,0,0,0-2.49,29.8L102,154l41.3,84.87A15.86,15.86,0,0,0,157.74,248q.69,0,1.38-.06a15.88,15.88,0,0,0,14-11.51l58.2-191.94c0-.05,0-.1,0-.15A16,16,0,0,0,227.32,28.68ZM157.83,231.85l-.05.14,0-.07-40.06-82.3,48-48a8,8,0,0,0-11.31-11.31l-48,48L24.08,98.25l-.07,0,.14,0L216,40Z"></path></svg>'))
            ->slideOver()
            ->fillForm(fn(array $arguments): array => array_merge(
                Macro\Theme::getArguments($arguments),
                Macro\SectionHeader::getArguments($arguments),
                Macro\ButtonsRepeater::getArguments($arguments),
                [
                    'personalized_recipient' => $arguments['personalized_recipient'] ?? false,
                    'mail_to' => $arguments['mail_to'] ?? null,
                    'body' => $arguments['body'] ?? null,
                ],
            ))
            ->form([
                Macro\SectionHeader::getFields(),
                Placeholder::make('form')->label('Form'),
                Toggle::make('personalized_recipient')
                    ->live()
                    ->label('Destinatario personalizzato')
                    ->default(false),
                TextInput::make('mail_to')
                    ->visible(fn(Get $get) => $get('personalized_recipient'))
                    ->label('Destinatario')
                    ->helperText('L\'email del destinatario del form di contatto')
                    ->email()
                    ->required(),
                Textarea::make('body')
                    ->label('Messaggio preimpostato'),
                Macro\ButtonsRepeater::getFields(),
                Macro\Theme::getFields(),
            ])
            ->action(function (array $arguments, array $data, Mason $component) {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setBrick',
                            arguments: [[
                                'identifier' => 'form',
                                'values' => $data,
                                'path' => 'mason.form',
                                'view' => view('mason.form', $data)->toHtml(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
