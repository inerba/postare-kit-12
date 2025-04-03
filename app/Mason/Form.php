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

class Form
{
    public static function make(): Brick
    {
        return Brick::make('form')
            ->label('Form')
            ->modalHeading('Form Settings')
            ->icon('heroicon-o-cube-transparent')
            ->slideOver()
            ->fillForm(fn (array $arguments): array => array_merge(
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
                    ->visible(fn (Get $get) => $get('personalized_recipient'))
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
