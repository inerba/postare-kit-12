<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Mason\BrickCollection;
use App\Mason\PostBrickCollection;
use App\Models\Post;
use App\Traits\HasSeoFields;
use App\Traits\HasSocialFields;
use Awcodes\Mason\Mason;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Actions;

class PostResource extends Resource implements HasShieldPermissions
{

    use HasSeoFields, HasSocialFields;

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Mason::make('content')
                    ->label(false)
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
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
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
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ])
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
                                    ->rules(Rule::dimensions()->maxWidth(600)->maxHeight(800))
                                    ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\SpatieMediaLibraryFileUpload $component) {
                                        $livewire->validateOnly($component->getStatePath());
                                    }),
                                Forms\Components\Toggle::make('extras.show_featured_image')
                                    ->label('Mostra nella testata')
                                    ->default(true),
                            ]),


                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Select::make('author_id')
                                    ->label('Author')
                                    ->relationship('author', 'name')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->default(now())
                                    ->visible(auth()->user()->can('publish_post')),
                            ]),

                        Actions::make([
                            Actions\Action::make('Link')
                                ->visible(fn($livewire) => $livewire->record !== null)
                                ->label(fn(Post $post) => route('blog.post', $post))
                                ->link()
                                ->url(fn(Post $post) => route('blog.post', $post), true),
                        ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('author.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
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
}
