<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Mason\Collections\PostBrickCollection;
use App\Models\Post;
use App\Traits\HasSeoFields;
use App\Traits\HasSocialFields;
use Awcodes\Mason\Mason;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostResource extends Resource implements HasShieldPermissions
{
    use HasSeoFields, HasSocialFields;

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('posts.post.label');
    }

    public static function getPluralLabel(): string
    {
        return __('posts.post.plural_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('posts.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('posts.post.navigation_label');
    }

    public static function form(Form $form): Form
    {

        /** @var \App\Models\User */
        $user = Auth::user();

        return $form
            ->schema([
                Mason::make('content')
                    ->label(null)
                    ->bricks(PostBrickCollection::make())
                    // optional
                    ->placeholder('Trascina e rilascia i componenti per iniziare...')
                    ->columnSpanFull(),
                Forms\Components\Tabs::make('Tabs')
                    ->columnSpan(2)
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Principale')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Titolo')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required(),
                                Forms\Components\Textarea::make('excerpt')
                                    ->required(),
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('Generate excerpt')
                                        ->label('Genera riassunto')
                                        ->action(function (Forms\Get $get, Set $set) {
                                            $content = $get('content');

                                            if (! $content) {
                                                return;
                                            }

                                            $text = '';
                                            foreach ($content['content'] as $item) {
                                                $text .= preg_replace('/\s+/', ' ', str($item['attrs']['view'])->stripTags());

                                                if (strlen($text) > 300) {
                                                    break;
                                                }
                                            }

                                            $set('excerpt', str($text)->words(45, end: ''));
                                        })
                                        ->size(ActionSize::ExtraSmall),
                                ]),
                                Forms\Components\Select::make('tags')
                                    ->multiple()
                                    ->relationship('tags', 'name'),

                                Forms\Components\Select::make('category_id')
                                    ->label('Categoria')
                                    ->relationship('category', 'name')
                                    ->createOptionForm($user->can('create_category') ? [
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ] : null)
                                    ->required(),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('pages.resources.page.form.tab_seo'))->schema(self::getSeoFields('meta')),
                        Forms\Components\Tabs\Tab::make(__('pages.resources.page.form.tab_social'))->schema(self::getSocialFields('meta')),
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Immagine in evidenza')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('featured_image')
                                    ->live()
                                    ->image()
                                    ->hiddenLabel()
                                    ->collection('featured_image')
                                    ->directory('posts')
                                    // ->rules(Rule::dimensions()->maxWidth(600)->maxHeight(800))
                                    ->afterStateUpdated(function ($livewire, Forms\Components\SpatieMediaLibraryFileUpload $component) {
                                        $livewire->validateOnly($component->getStatePath());
                                    }),
                                Forms\Components\Toggle::make('extras.show_featured_image')
                                    ->label('Mostra nella testata')
                                    ->default(true),
                            ]),

                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Select::make('author_id')
                                    ->label('Autore')
                                    ->relationship('author', 'name')
                                    ->required()
                                    ->default(fn () => $user->id)
                                    ->disabled(fn () => ! $user->hasRole('super_admin'))
                                // ->visible(fn() => $user !== null)
                                ,
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Data di pubblicazione')
                                    ->default(now())
                                    ->visible($user->can('publish_post')),
                            ]),

                        Actions::make([
                            Actions\Action::make('Link')
                                ->visible(fn ($livewire) => $livewire->record !== null)
                                ->label(fn (Post $post) => $post->permalink)
                                ->link()
                                ->url(fn (Post $post) => $post->permalink, true),
                        ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('featured_image')
                    ->collection('featured_image')
                    ->conversion('icon')
                    ->label('Copertina')
                    ->size(90),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titolo')
                    ->description(fn (Post $record) => Str::limit($record->excerpt, 100))
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Autore')
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
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Pubblicazione'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->preload(),

                SelectFilter::make('author_id')
                    ->label('Autore')
                    ->relationship('author', 'name', fn ($query) => $query->has('posts'))
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('view_permalink')
                        ->label('Visualizza post')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('success')
                        ->url(fn (Post $record) => $record->permalink, true),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    /**
     * Get the permission prefixes for this resource.
     *
     * @return array<string>
     */
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'publish',
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }
}
