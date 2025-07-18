<?php

namespace App\Filament\Pages;

use App\Filament\Actions\Forms\HtmlCleanAction;
use App\Mason\Collections\PageBrickCollection;
use App\Traits\HasSeoFields;
use App\Traits\HasSocialFields;
use Awcodes\Mason\Mason;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use FilamentTiptapEditor\TiptapEditor;
use Postare\DbConfig\AbstractPageSettings;

class HomePageSettingsPage extends AbstractPageSettings
{
    use HasPageShield, HasSeoFields, HasSocialFields;

    public ?array $data = [];

    protected static ?string $title = 'HomePage';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected ?string $subheading = '';

    protected static string $view = 'filament.config-pages.homepage';

    public static function getNavigationGroup(): string
    {
        return __('pages.navigation_group');
    }

    protected function settingName(): string
    {
        return 'homepage';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Testo')
                            ->schema([
                                Mason::make('content')
                                    ->label(null)
                                    ->bricks(PageBrickCollection::make())
                                    // optional
                                    ->placeholder('Trascina e rilascia i componenti per iniziare...')
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Slider')
                            ->schema([
                                Select::make('slider_settings.height')
                                    ->label('Altezza slider')
                                    ->options([
                                        'small' => 'Piccolo',
                                        'medium' => 'Medio',
                                        'large' => 'Grande',
                                        'full' => 'Intero schermo',
                                    ])
                                    ->default('medium')
                                    ->required(),
                                Repeater::make('slides')
                                    ->collapsible()
                                    ->collapsed()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                    ->schema([
                                        Tabs::make('Tabs')
                                            ->contained(false)
                                            ->tabs([
                                                Tabs\Tab::make('Testo')
                                                    ->columns(2)
                                                    ->schema([
                                                        TextInput::make('title')
                                                            ->label('Titolo'),

                                                        TextInput::make('subtitle')
                                                            ->label('Sottotitolo'),

                                                        TiptapEditor::make('content')
                                                            ->label('Contenuto')
                                                            ->placeholder('Content')
                                                            ->hintAction(HtmlCleanAction::make())
                                                            ->columnSpanFull(),

                                                        TextInput::make('button_text')
                                                            ->label('Testo del bottone'),

                                                        TextInput::make('button_link')
                                                            ->label('Link'),

                                                        Select::make('width')
                                                            ->label('Larghezza del testo')
                                                            ->options([
                                                                'small' => 'Piccolo',
                                                                'medium' => 'Medio',
                                                                'large' => 'Grande',
                                                            ])
                                                            ->default('medium')
                                                            ->required(),

                                                    ]),
                                                Tabs\Tab::make('Elementi multimediali')
                                                    ->columns(2)
                                                    ->schema([

                                                        Toggle::make('is_video')
                                                            ->live()
                                                            ->label('Video')
                                                            ->columnSpanFull()
                                                            ->default(false),

                                                        TextInput::make('duration')
                                                            ->label('Durata (in secondi)')
                                                            ->numeric()
                                                            ->default(5)
                                                            ->required()
                                                            ->columnSpanFull(),

                                                        FileUpload::make('video_mp4')
                                                            ->hidden(fn (Get $get) => $get('is_video') === false)
                                                            ->label('Video in formato MP4')
                                                            ->directory('home-slider-videos')
                                                            ->required()
                                                            ->acceptedFileTypes(['video/mp4']),

                                                        FileUpload::make('video_webm')
                                                            ->hidden(fn (Get $get) => $get('is_video') === false)
                                                            ->label('Video in formato WEBM')
                                                            ->directory('home-slider-videos')
                                                            ->acceptedFileTypes(['video/webm']),

                                                        FileUpload::make('image')
                                                            ->label(fn (Get $get) => $get('is_video') === true ? 'Immagine di fallback' : 'Immagine di sfondo')
                                                            ->directory('home-slider-images')
                                                            ->image()
                                                            ->imageEditor()
                                                            ->columnSpanFull(),

                                                    ]),
                                            ]),
                                    ])
                                    ->addActionLabel('Nuova slide'),

                            ]),
                        Tabs\Tab::make('SEO')->schema(self::getSeoFields('seo')),
                        Tabs\Tab::make('Social')->schema(self::getSocialFields('social')),
                    ]),
            ])
            ->statePath('data');
    }
}
