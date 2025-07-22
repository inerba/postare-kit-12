<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function getLabel(): string
    {
        return __('posts.category.label');
    }

    public static function getPluralLabel(): string
    {
        return __('posts.category.plural_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('posts.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('posts.category.navigation_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')
                    ->hintAction(
                        Forms\Components\Actions\Action::make('permalink')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->color('primary')
                            ->tooltip('Apri la categoria in una nuova scheda')
                            ->url(fn ($record) => $record->permalink, true)
                            ->label(fn ($record) => $record->permalink)
                            ->hidden(fn ($context) => $context === 'create'),
                    )
                    ->label('Slug')
                    ->required(),

                TiptapEditor::make('extras.description')
                    ->label('Descrizione')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('extras.post_per_page')
                    ->label('Post per pagina')
                    ->numeric()
                    ->minValue(6)
                    ->default(12)
                    ->maxValue(48)
                    ->rule('multiple_of:6')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Category $record) => isset($record->extras['description']) ? Str::limit(strip_tags($record->extras['description']), 100) : 'Nessuna descrizione')
                    ->label('Nome')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('posts_count')
                    ->label('Post')
                    ->counts('posts')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creazione')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Aggiornamento')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('view_permalink')
                        ->label('Visualizza categoria')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('success')
                        ->url(fn (Category $record) => $record->permalink, true),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
