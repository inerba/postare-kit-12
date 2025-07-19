<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Models\Author;
use App\Models\User;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $slug = 'authors';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?int $navigationSort = 6;

    public static function getLabel(): string
    {
        return __('pages.resources.author.singular');
    }

    public static function getPluralLabel(): string
    {
        return __('pages.resources.author.plural');
    }

    public static function getNavigationGroup(): string
    {
        return __('pages.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('pages.resources.author.plural');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->schema([

                    TextInput::make('name')
                        ->label(__('pages.resources.author.name'))
                        ->columnSpanFull()
                        ->required(),

                    RichEditor::make('bio')
                        ->label(__('pages.resources.author.bio'))
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'link',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->columnSpan(2),
            Section::make()
                ->schema([

                    SpatieMediaLibraryFileUpload::make('avatar')
                        ->label(__('pages.resources.author.avatar'))
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            null,
                            '1:1',
                        ])
                        ->openable()
                        ->image()
                        ->collection('author_avatar'),

                    Select::make('user')
                        ->label(__('pages.resources.author.user'))
                        ->relationship('user', 'name')
                        ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->name} ({$record->email})")
                        ->searchable(['name', 'email'])
                        ->preload()
                        ->dehydrated(false),

                ])->columnSpan(1),

        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([

            SpatieMediaLibraryImageColumn::make('avatar')
                //                ->label(__('pages.resources.author.avatar'))
                ->label(null)
                ->toggleable(isToggledHiddenByDefault: false)
                ->height(90)
                ->circular()
                ->limit(1)
                ->limitedRemainingText()
                ->conversion('icon')
                ->collection('author_avatar'),

            TextColumn::make('name')
                ->label(__('pages.resources.author.name'))
                ->toggleable(isToggledHiddenByDefault: false)
                ->description(fn (Author $author) => $author->user->email ?? false)
                ->searchable()
                ->sortable(),

            TextColumn::make('bio')
                ->label(__('pages.resources.author.bio'))
                ->toggleable(isToggledHiddenByDefault: false)
                ->formatStateUsing(fn ($state) => str($state)->stripTags()->words(30))
                ->wrap(),

            TextColumn::make('pages_count')
                ->label(__('pages.resources.author.pages_count'))
                ->toggleable(isToggledHiddenByDefault: true)
                ->counts('pages')
                ->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuthors::route('/'),
            'create' => Pages\CreateAuthor::route('/create'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}
