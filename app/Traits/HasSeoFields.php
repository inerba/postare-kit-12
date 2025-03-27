<?php

namespace App\Traits;

use Filament\Forms;

trait HasSeoFields
{
    public static function remainingText($state, $maxCharacters = 60)
    {
        $charactersCount = strlen($state);
        $leftCharacters = $maxCharacters - ($charactersCount % $maxCharacters);

        return "$charactersCount / $maxCharacters ($leftCharacters)";
    }

    protected static function getSeoFields($prefix): array
    {
        return [
            Forms\Components\TextInput::make($prefix.'.tag_title')
                ->hint(fn ($state): string => self::remainingText($state, 60))
                ->live()
                ->label('Tag title')
                ->helperText('Il tag title è il titolo che verrà mostrato nei risultati di ricerca di Google.')
                ->columnSpanFull(),
            Forms\Components\Textarea::make($prefix.'.meta_description')
                ->hint(fn ($state): string => self::remainingText($state, 160))
                ->live()
                ->label('Meta description')
                ->helperText('La meta description è la descrizione che verrà mostrata nei risultati di ricerca di Google.')
                ->columnSpanFull(),
        ];
    }
}
