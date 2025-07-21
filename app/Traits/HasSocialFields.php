<?php

namespace App\Traits;

use Filament\Forms;

trait HasSocialFields
{
    /**
     * Restituisce i campi social per una resource specifica.
     *
     * @param  string  $prefix  Il prefisso per i campi social.
     * @return array<int, Forms\Components\Component> Un array di componenti Filament.
     */
    protected static function getSocialFields($prefix): array
    {
        return [
            Forms\Components\Section::make([
                Forms\Components\TextInput::make($prefix.'.og.title')
                    ->hint(fn ($state): string => self::remainingText($state, 60))
                    ->live()
                    ->label(__('pages.social.title'))
                    ->helperText(__('pages.social.title_helper'))
                    ->columnSpanFull(),
                Forms\Components\Textarea::make($prefix.'.og.description')
                    ->hint(fn ($state): string => self::remainingText($state, 200))
                    ->live()
                    ->label(__('pages.social.description'))
                    ->helperText(__('pages.social.description_helper'))
                    ->columnSpanFull(),
            ]),
            Forms\Components\Section::make([
                Forms\Components\SpatieMediaLibraryFileUpload::make('og_image')
                    ->label(__('pages.social.image'))
                    ->helperText(__('pages.social.image_helper'))
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->openable()
                    ->image()
                    ->collection('og_image'),
            ]),
        ];
    }
}
