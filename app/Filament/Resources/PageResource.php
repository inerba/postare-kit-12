<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages\CreatePage;
use App\Filament\Resources\PageResource\Pages\EditPage;
use App\Filament\Resources\PageResource\Pages\ListPages;
use App\Mason\BrickCollection;
use App\Models\Author;
use App\Models\Page;
use App\Traits\HasSeoFields;
use App\Traits\HasSocialFields;
use Awcodes\Mason\Mason;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

// use Postare\Blog\Filament\Resources\PageResource\Pages\CreatePage;
// use Postare\Blog\Filament\Resources\PageResource\Pages\EditPage;
// use Postare\Blog\Filament\Resources\PageResource\Pages\ListPages;
// use Postare\Blog\Helpers\CleanHtml;
// use Postare\Blog\Models\Author;
// use Postare\Blog\Models\Page;
// use Postare\Blog\Traits\HasSeoFields;
// use Postare\Blog\Traits\HasSocialFields;

class PageResource extends Resource
{
    use HasSeoFields;
    use HasSocialFields;
    use Translatable;

    protected static ?string $model = Page::class;

    protected static ?string $slug = 'pages';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('pages.resources.page.singular');
    }

    public static function getPluralLabel(): string
    {
        return __('pages.resources.page.plural');
    }

    public static function getNavigationGroup(): string
    {
        return __('pages.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('pages.resources.page.plural');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Mason::make('content')
                ->label(false)
                ->bricks(BrickCollection::make())
                // optional
                ->placeholder('Trascina e rilascia i componenti per iniziare...')
                ->columnSpanFull(),
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Tabs::make()
                        ->tabs([
                            Forms\Components\Tabs\Tab::make(__('pages.resources.page.form.tab_content'))
                                ->columns(2)
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->columnSpanFull()
                                        ->label(__('pages.resources.page.form.title'))
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Set $set, ?string $state, $context) {
                                            if ($context === 'edit') {
                                                return;
                                            }

                                            $set('slug', Str::slug($state));
                                        }),

                                    Forms\Components\Hidden::make('lock_slug')
                                        ->live(false, 500)
                                        ->afterStateHydrated(fn (Set $set, $context) => $set('lock_slug', $context === 'edit'))
                                        ->dehydrated(false),

                                    Forms\Components\TextInput::make('slug')
                                        ->label(__('pages.resources.page.form.slug'))
                                        ->disabled(fn (Get $get) => $get('lock_slug'))
                                        ->helperText(fn ($context) => $context === 'edit' ? __('pages.resources.page.form.slug_help') : null)
                                        ->hintAction(
                                            fn ($context) => $context === 'edit' ?
                                                Action::make('toggle_lock_slug')
                                                    ->icon(fn (Get $get) => $get('lock_slug') ? 'heroicon-s-lock-closed' : 'heroicon-s-lock-open')
                                                    ->label(false)
                                                    ->action(fn (Set $set, Get $get) => $set('lock_slug', ! $get('lock_slug')))
                                                : null
                                        )
                                        ->rules(['alpha_dash'])
                                        ->unique(ignoreRecord: true)
                                        ->required(),

                                    Select::make('parent_id')
                                        ->label(__('pages.resources.menu_item.form.parent'))
                                        ->live()
                                        ->placeholder(__('pages.resources.menu_item.form.parent_placeholder'))
                                        ->searchable()
                                        ->preload()
                                        ->options(fn ($record) => Page::query()
                                            ->whereNull('parent_id')
                                            ->when($record, function ($query, $record) {
                                                return $query->where('id', '!=', $record->id);
                                            })
                                            ->pluck('title', 'id'))
                                        ->nullable(),

                                    Forms\Components\Textarea::make('lead')
                                        ->label(__('pages.resources.page.form.lead'))
                                        ->columnSpanFull(),

                                ]),
                            Forms\Components\Tabs\Tab::make(__('pages.resources.page.form.tab_custom_fields'))
                                ->visible(fn ($context) => $context === 'edit' && $form->model->hasPlaceholders())
                                ->schema(fn ($context): array => $context === 'edit' ? $form->model->getPlaceholders() : []),
                            Forms\Components\Tabs\Tab::make(__('pages.resources.page.form.tab_seo'))->schema(self::getSeoFields('meta', ['title', 'content'])),
                            Forms\Components\Tabs\Tab::make(__('pages.resources.page.form.tab_social'))->schema(self::getSocialFields('meta')),
                        ]),

                ])->columnSpan(2),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make(__('pages.resources.page.form.featured_images'))
                        ->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('featured_images')
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    null,
                                    '16:9',
                                    '4:3',
                                    '1:1',
                                ])
                                ->live()
                                ->multiple()
                                ->reorderable()
                                ->appendFiles()
                                ->openable()
                                ->image()
                                ->hiddenLabel()
                                ->collection('featured_images')
                            // ->rules(Rule::dimensions()->maxWidth(600)->maxHeight(800))
                            // ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\SpatieMediaLibraryFileUpload $component) {
                            //     $livewire->validateOnly($component->getStatePath());
                            // })
                            ,
                            Forms\Components\Toggle::make('extras.show_featured_image')
                                ->label('Mostra immagine nella testata')
                                ->default(true),
                        ]),

                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\Select::make('author_id')
                                ->label(__('pages.resources.page.form.author'))
                                ->relationship('author', 'name')
                                ->default(Author::first()?->id)
                                ->native(false)
                                ->required(),

                            Forms\Components\Actions::make([
                                Forms\Components\Actions\Action::make('permalink')
                                    ->size(ActionSize::Small)
                                    ->link()
                                    ->icon('heroicon-o-link')
                                    ->color('gray')
                                    ->url(fn ($record) => $record->permalink, true)
                                    ->label(__('pages.resources.post.form.permalink')),
                            ])
                                ->hidden(fn ($context) => $context === 'create')
                                ->columnSpanFull(),
                        ]),
                ])->columnSpan(1),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([

            TextColumn::make('title')
                ->label(__('pages.resources.page.table.title'))
                ->size('xl')
                ->description(fn (Page $record): mixed => $record->hasCustomView() ? __('pages.resources.page.table.has_custom_view') : false)
                ->searchable()
                ->sortable(),

            TextColumn::make('permalink')
                ->label(__('pages.resources.page.table.permalink'))
                ->wrap()
                ->toggleable(isToggledHiddenByDefault: false)
                ->icon('heroicon-o-link')
                ->url(fn ($state) => $state, true)
                ->formatStateUsing(fn ($state) => str()->of($state)->replace(url('/'), '')),

            SpatieMediaLibraryImageColumn::make('featured_images')
                ->label(__('pages.resources.page.table.featured_images'))
                ->toggleable(isToggledHiddenByDefault: false)
                ->conversion('icon')
                ->height(60)
                ->circular()
                ->stacked()
                ->overlap(8)
                ->limit(2)
                ->limitedRemainingText()
                ->collection('featured_images'),

            IconColumn::make('has_custom_view')
                ->label(__('pages.resources.page.table.has_custom_view'))
                ->toggleable(isToggledHiddenByDefault: true)
                ->state(fn (Page $record): bool => $record->hasCustomView())
                ->trueIcon('heroicon-s-bolt')
                ->falseIcon('heroicon-s-bolt-slash')
                ->trueColor('warning')
                ->falseColor('gray')
                ->boolean(),

            TextColumn::make('author')
                ->label(__('pages.resources.page.table.author'))
                ->toggleable(isToggledHiddenByDefault: true)
                ->formatStateUsing(fn (Page $record): string => $record->author->name)
                ->description(fn (Page $record): string => $record->author?->bio ?? false),
        ])->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }
}
