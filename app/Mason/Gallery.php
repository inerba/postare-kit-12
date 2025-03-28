<?php

namespace App\Mason;

use Awcodes\Mason\Brick;
use Awcodes\Mason\EditorCommand;
use Awcodes\Mason\Mason;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Get;
use Illuminate\Support\Str;

class Gallery
{
    public static function make(): Brick
    {
        return Brick::make('gallery')
            ->label('Gallery')
            ->modalHeading('Gallery Settings')
            ->icon('heroicon-o-cube-transparent')
            ->slideOver()
            ->fillForm(fn (array $arguments): array => array_merge(
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
                    ->collection(fn (Get $get) => $get('gallery_rand').'_gallery')
                    ->directory('blog_photo_gallery')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->image()
                    ->imageEditor()
                    ->openable()
                    ->downloadable()
                    // ->minFiles(1)
                    ->maxFiles(20)
                    ->conversion('thumbnail')
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
