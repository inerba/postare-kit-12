<?php

namespace App\Mason;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Awcodes\Matinee\Matinee;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Split
{
    public static function make(): Brick
    {
        return Brick::make('split')
            ->label('Sezione splittata')
            ->modalHeading('Impostazioni sezione splittata')
            ->icon(new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 256 256"><path d="M200,42H56A14,14,0,0,0,42,56V200a14,14,0,0,0,14,14H200a14,14,0,0,0,14-14V56A14,14,0,0,0,200,42Zm-66,76h68v20H134Zm0-12V86h68v20Zm0,44h68v20H134Zm68-94V74H134V54h66A2,2,0,0,1,202,56ZM54,200V56a2,2,0,0,1,2-2h66V202H56A2,2,0,0,1,54,200Zm146,2H134V182h68v18A2,2,0,0,1,200,202Z"></path></svg>'))
            ->slideOver()
            ->fillForm(fn (array $arguments): array => array_merge(
                array_merge(
                    Macro\Theme::getArguments($arguments),
                    ['theme' => [
                        'blockMaxWidth' => $arguments['theme']['blockMaxWidth'] ?? 'max-w-7xl',
                        'blockVerticalPadding' => $arguments['theme']['blockVerticalPadding'] ?? null,
                        'blockVerticalPaddingLg' => $arguments['theme']['blockVerticalPaddingLg'] ?? null,
                    ]],
                ),
                Macro\SectionHeader::getArguments($arguments),
                Macro\ButtonsRepeater::getArguments($arguments),
                [
                    'layout' => $arguments['layout'] ?? 'txt_img',
                    'text' => $arguments['text'] ?? null,
                    'image' => $arguments['image'] ?? null,
                    'video' => $arguments['video'] ?? null,
                    'img_cover' => $arguments['img_cover'] ?? false,
                ],
            ))
            ->form([
                Macro\SectionHeader::getFields(),
                Select::make('layout')
                    ->label('Layout')
                    ->live()
                    ->options([
                        'txt_img' => 'Testo a sinistra, immagine a destra',
                        'img_txt' => 'Testo a destra, immagine a sinistra',
                        'txt_vid' => 'Testo a sinistra, video a destra',
                        'vid_txt' => 'Video a sinistra, testo a destra',
                    ])
                    ->required(),

                TiptapEditor::make('text')
                    ->label('Testo')
                    ->required(),

                FileUpload::make('image')
                    ->label('Immagine')
                    ->visible(fn (Get $get) => Str::contains($get('layout'), 'img'))
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

                Toggle::make('img_cover')
                    ->label('Immagine a copertura')
                    ->visible(fn (Get $get) => Str::contains($get('layout'), 'img')),

                Matinee::make('video')
                    ->label('Video')
                    ->visible(fn (Get $get) => Str::contains($get('layout'), 'vid'))
                    ->showPreview(),

                Macro\ButtonsRepeater::getFields(),

                Macro\Theme::getFields(),
            ])
            ->action(function (array $arguments, array $data, Mason $component) {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setBrick',
                            arguments: [[
                                'identifier' => 'split',
                                'values' => $data,
                                'path' => 'mason.split',
                                'view' => view('mason.split', $data)->toHtml(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
