<?php

namespace App\Filament\Resources\MenuResource\MenuTypeHandlers;

use App\Filament\Resources\MenuResource\Traits\CommonFieldsTrait;
use App\Models\Category;
use Filament\Forms\Components;
use Filament\Forms\Get;
use Filament\Forms\Set;

class PostCategoryHandler implements MenuTypeInterface
{
    use CommonFieldsTrait;

    public function getName(): string
    {
        return 'Categoria Post';
    }

    /**
     * Restituisce i campi specifici per il tipo di menu "Categoria Post".
     *
     * @return array<int, Components\Component>
     */
    public static function getFields(): array
    {
        return [
            Components\Select::make('parent_id')
                ->label('Categoria')
                ->options(fn () => Category::pluck('name', 'id')->toArray())
                ->required()
                ->dehydrated()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    if (! $state) {
                        return;
                    }

                    $category = Category::find($state);
                    if (! $category) {
                        return;
                    }

                    $set('url', $category->relativePermalink);

                    $set('label', Category::find($state)->name);
                })
                ->columnSpanFull(),
            Components\TextInput::make('url')
                ->readOnly()
                ->label('URL')
                ->hidden(fn (Get $get) => $get('parent_id') == null)
                ->required()
                ->columnSpanFull(),

            // Common fields for all menu types
            Components\Section::make(__('simple-menu-manager.common.advanced_settings'))
                ->schema(self::commonLinkFields())
                ->collapsed(),
        ];
    }
}
