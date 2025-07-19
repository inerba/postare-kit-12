<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\MenuTypes;
use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    public static function getModelLabel(): string
    {
        return config('simple-menu-manager.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return config('simple-menu-manager.plural_model_label');
    }

    public static function getNavigationSort(): ?int
    {
        return config('simple-menu-manager.navigation_sort');
    }

    public static function getNavigationGroup(): string
    {
        return config('simple-menu-manager.navigation_group');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('simple-menu-manager.resource.name'))
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, Forms\Set $set) {
                        if (! $state) {
                            return;
                        }

                        $set('slug', Str::slug($state));
                    })
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->label(__('simple-menu-manager.resource.slug'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabledOn('edit')
                    ->maxLength(255),
                AdjacencyList::make('items')
                    ->label(__('simple-menu-manager.resource.items'))
                    ->maxDepth(2)
                    ->addAction(fn (Action $action): Action => $action
                        ->label(__('simple-menu-manager.resource.add_item'))
                        ->icon('heroicon-o-plus')
                        ->color('primary'))
                    ->columnSpanFull()
                    ->labelKey('label')
                    ->childrenKey('children')
                    ->form([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label(__('simple-menu-manager.resource.type'))
                                    ->live()
                                    ->options(MenuTypes::getTypes())
                                    ->default('link')
                                    ->required(),

                                Forms\Components\TextInput::make('label')
                                    ->label(__('simple-menu-manager.resource.label'))
                                    ->required(),
                            ]),
                        Grid::make()
                            ->hidden(fn (Get $get) => $get('type') == null)
                            ->reactive()
                            ->columns(2)
                            ->schema(function (Get $get) {
                                return MenuTypes::getFieldsByType($get('type'));
                            }),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
