<?php

namespace App\Filament\Pages;

use App\Traits\HasSeoFields;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Dotswan\FilamentCodeEditor\Fields\CodeEditor;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Arr;
use Postare\DbConfig\AbstractPageSettings;
use Postare\DbConfig\DbConfig;

class GeneralSettingsPage extends AbstractPageSettings
{
    use HasPageShield, HasSeoFields;

    public ?array $data = [];

    protected static ?string $title = 'Impostazioni generali';

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected ?string $subheading = '';

    protected static string $view = 'filament.config-pages.general';

    protected function settingName(): string
    {
        return 'general';
    }

    public function mount(): void
    {
        $this->data = DbConfig::getGroup($this->settingName());

        $defaults = [
            'cookieconsent' => [
                'enabled' => true,
                'title' => 'Utilizziamo i cookie',
                'description' => 'Utilizziamo i cookie per migliorare la tua esperienza sul nostro sito. Alcuni cookie sono necessari per il funzionamento del sito, mentre altri ci aiutano a capire come interagisci con esso.',
                'layout' => 'box',
                'layout_variant' => 'wide',
                'positionX' => 'none',
                'positionY' => 'bottom',
            ],
        ];

        // Applica i values di default dove il dato è null o non esiste
        foreach (Arr::dot($defaults) as $key => $default) {
            if (data_get($this->data, $key) === null) {
                data_set($this->data, $key, $default);
            }
        }

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([Forms\Components\Tabs::make('Tabs')
                ->tabs([

                    Forms\Components\Tabs\Tab::make('Contatti')
                        ->schema([
                            Forms\Components\Grid::make()
                                ->columns(3)
                                ->schema([
                                    Forms\Components\Section::make('Mittente')
                                        ->description('L\'indirizzo utilizzato come mittente per i messaggi inviati dai moduli di contatto.')
                                        ->schema([
                                            Forms\Components\TextInput::make('mail_from_name')
                                                ->hintIcon('heroicon-o-tag')
                                                ->hintIconTooltip('general.mail_from_name')
                                                ->label('Nome')
                                                ->required(),
                                            Forms\Components\TextInput::make('mail_from_email')
                                                ->hintIcon('heroicon-o-tag')
                                                ->hintIconTooltip('general.mail_from_email')
                                                ->label('Email')
                                                ->required(),
                                        ])->columnSpan(1),
                                    Forms\Components\Section::make('Destinatario')
                                        ->description('L\'indirizzo di posta elettronica che riceverà i messaggi inviati dai moduli di contatto.')
                                        ->schema([
                                            Forms\Components\TextInput::make('mail_to_name')
                                                ->hintIcon('heroicon-o-tag')
                                                ->hintIconTooltip('general.mail_to_name')
                                                ->label('Nome')
                                                ->required(),
                                            Forms\Components\TextInput::make('mail_to_email')
                                                ->hintIcon('heroicon-o-tag')
                                                ->hintIconTooltip('general.mail_to_email')
                                                ->label('Email')
                                                ->required(),
                                        ])->columnSpan(1),

                                    Forms\Components\Section::make('Casella per risposte')
                                        ->description('L\'indirizzo di posta elettronica al quale gli utenti potranno rispondere.')
                                        ->schema([
                                            Forms\Components\TextInput::make('reply_to_name')
                                                ->hintIcon('heroicon-o-tag')
                                                ->hintIconTooltip('general.reply_to_name')
                                                ->label('Nome')
                                                ->required(),
                                            Forms\Components\TextInput::make('reply_to_email')
                                                ->hintIcon('heroicon-o-tag')
                                                ->hintIconTooltip('general.reply_to_email')
                                                ->label('Email')
                                                ->required(),
                                        ])->columnSpan(1),
                                ]),
                        ]),

                    Forms\Components\Tabs\Tab::make('Social')
                        ->schema([
                            Forms\Components\Repeater::make('social_profiles')
                                ->schema([
                                    Forms\Components\FileUpload::make('icon')
                                        ->helperText('Dimensione consigliata: 32x32px, Formato: png')
                                        ->directory('social-icons'),
                                    Forms\Components\TextInput::make('title')->required(),
                                    Forms\Components\TextInput::make('url')->required(),
                                ])
                                ->columns(3),
                        ]),
                    Forms\Components\Tabs\Tab::make('SEO')
                        ->schema(
                            [
                                Forms\Components\Fieldset::make('Home page')->schema(self::getSeoFields('seo.home')),
                            ]
                        ),

                    Forms\Components\Tabs\Tab::make('Tracking')
                        ->columns(2)
                        ->schema([
                            Fieldset::make('Aspetto banner cookies')
                                ->schema([

                                    Forms\Components\TextInput::make('cookieconsent.title')
                                        ->label('Titolo del banner')
                                        ->default('Questo sito utilizza i cookies')
                                        ->columnSpanFull()
                                        ->required(),

                                    Forms\Components\TextArea::make('cookieconsent.description')
                                        ->label('Messaggio del banner')
                                        ->default('Questo sito utilizza i cookies per garantire la migliore esperienza di navigazione.')
                                        ->columnSpanFull()
                                        ->required(),

                                    Forms\Components\Select::make('cookieconsent.layout')
                                        ->label('Layout')
                                        ->live(true)
                                        ->options([
                                            'box' => 'Box',
                                            'cloud' => 'Cloud',
                                            'bar' => 'Bar',
                                        ])
                                        ->default('box')
                                        ->required(),

                                    Forms\Components\Select::make('cookieconsent.layout_variant')
                                        ->label('Variante')
                                        ->options(fn(Get $get) => match ($get('cookieconsent.layout')) {
                                            'box' => [
                                                'wide' => 'Wide',
                                                'inline' => 'Inline',
                                                '' => 'None',
                                            ],
                                            default => [
                                                'inline' => 'Inline',
                                                '' => 'None',
                                            ]
                                        })
                                        ->default(''),

                                    Forms\Components\Select::make('cookieconsent.positionX')
                                        ->label('Posizione orizzontale')
                                        ->options(fn(Get $get) => match ($get('cookieconsent.layout')) {
                                            'box' => [
                                                '' => 'None',
                                            ],
                                            default => [
                                                'left' => 'Left',
                                                'right' => 'Right',
                                                'center' => 'Center',
                                            ]
                                        })
                                        ->default('center'),

                                    Forms\Components\Select::make('cookieconsent.positionY')
                                        ->label('Posizione verticale')
                                        ->options(fn(Get $get) => match ($get('cookieconsent.layout')) {
                                            'bar' => [
                                                'top' => 'Top',
                                                'bottom' => 'Bottom',
                                            ],
                                            default => [
                                                'top' => 'Top',
                                                'bottom' => 'Bottom',
                                                'middle' => 'Middle',
                                            ]
                                        })
                                        ->default('middle'),
                                ]),
                            Repeater::make('track_and_cookies')
                                ->label('Codici di tracciamento e relativi Cookies')
                                ->columns(2)
                                ->columnSpanFull()
                                ->deleteAction(
                                    fn(Action $action) => $action->requiresConfirmation(),
                                )
                                ->schema([
                                    Forms\Components\Select::make('position')
                                        ->label('Posizione')
                                        ->options([
                                            'head' => 'HEAD',
                                            'body' => 'Inizio BODY',
                                            'footer' => 'Fine BODY',
                                        ])
                                        ->default('body')
                                        ->required(),
                                    Forms\Components\Select::make('category')
                                        ->label('Categoria')
                                        ->options([
                                            'analytics' => 'Analytics',
                                            'marketing' => 'Marketing',
                                            'necessary' => 'Necessari',
                                            'other' => 'Altro',
                                        ])
                                        ->default('analytics')
                                        ->required(),
                                    CodeEditor::make('code')
                                        ->label('Codice')
                                        ->columnSpanFull()
                                        ->required(),

                                    Repeater::make('cookies')
                                        ->label('Tabella dei cookies')
                                        ->columns(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('name')
                                                ->label('Nome')
                                                ->required(),
                                            Forms\Components\TextInput::make('service')
                                                ->label('Servizio')
                                                ->required(),
                                            Forms\Components\TextInput::make('description')
                                                ->label('Descrizione')
                                                ->required(),
                                            Forms\Components\TextInput::make('expiration')
                                                ->label('Scadenza')
                                                ->required(),
                                        ])
                                        ->deleteAction(
                                            fn(Action $action) => $action->requiresConfirmation(),
                                        )
                                        ->addActionLabel('Aggiungi cookie')
                                        ->collapsible()
                                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ]),])
            ->statePath('data');
    }
}
