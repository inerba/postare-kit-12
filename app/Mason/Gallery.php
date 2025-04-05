<?php

namespace App\Mason;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Gallery
{
    public static function make(): Brick
    {
        return Brick::make('gallery')
            ->label('Gallery')
            ->modalHeading('Gallery Settings')
            ->icon(new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 256 256"><path d="M208,32H80A16,16,0,0,0,64,48V64H48A16,16,0,0,0,32,80V208a16,16,0,0,0,16,16H176a16,16,0,0,0,16-16V192h16a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM80,48H208v69.38l-16.7-16.7a16,16,0,0,0-22.62,0L93.37,176H80Zm96,160H48V80H64v96a16,16,0,0,0,16,16h96Zm32-32H116l64-64,28,28v36Zm-88-64A24,24,0,1,0,96,88,24,24,0,0,0,120,112Zm0-32a8,8,0,1,1-8,8A8,8,0,0,1,120,80Z"></path></svg>'))
            ->slideOver()
            ->fillForm(fn(array $arguments): array => array_merge(
                Macro\Theme::getArguments($arguments),
                Macro\SectionHeader::getArguments($arguments),
                [
                    'gallery_rand' => $arguments['gallery_rand'] ?? Str::random(),
                    'layout' => $arguments['layout'] ?? 'grid',
                    'thumbnail' => $arguments['thumbnail'] ?? 'thumbnail',
                    'perPage' => $arguments['perPage'] ?? 3,
                    'carouselGap' => $arguments['carouselGap'] ?? '0.5rem',
                    'pagination' => $arguments['pagination'] ?? true,
                    'arrows' => $arguments['arrows'] ?? true,
                    'rewind' => $arguments['rewind'] ?? true,
                    'columns' => $arguments['columns'] ?? 1,
                    'columnsSm' => $arguments['columnsSm'] ?? 2,
                    'columnsMd' => $arguments['columnsMd'] ?? null,
                    'columnsLg' => $arguments['columnsLg'] ?? 3,
                    'columnsXl' => $arguments['columnsXl'] ?? null,
                    'columns2xl' => $arguments['columns2xl'] ?? null,
                ],
            ))
            ->form([
                Macro\SectionHeader::getFields(),
                Hidden::make('gallery_rand'),
                SpatieMediaLibraryFileUpload::make('gallery')
                    ->label(false)
                    ->collection(fn(Get $get) => $get('gallery_rand') . '_gallery')
                    ->directory('page_gallery')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->image()
                    ->imageEditor()
                    ->openable()
                    ->downloadable()
                    // ->minFiles(1)
                    ->maxFiles(20)
                    ->conversion('lg')
                    // ->manipulations([
                    //     'thumbnail',
                    // ])
                    ->panelLayout('grid'),

                Macro\Gallery::getFields(),
                Macro\Theme::getFields(),
            ])
            ->action(function (array $arguments, array $data, Mason $component) {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setBrick',
                            arguments: [[
                                'identifier' => 'gallery',
                                'values' => $data,
                                'path' => 'mason.gallery',
                                'view' => view('mason.gallery', $data)->toHtml(),
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
