<?php

namespace App\Filament\Pages;

use App\Mail\ContactMail;
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
use Illuminate\Support\Facades\Mail;
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
        parent::mount();
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
            'mail_subject' => 'Contatto dal sito web',
            'mail_from_name' => config('app.name'),
            'mail_from_email' => config('mail.from.address'),
            'mail_to_name' => config('app.name'),
            'mail_to_email' => config('mail.from.address'),
            'reply_to_name' => config('app.name'),
            'reply_to_email' => config('mail.from.address'),
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

                                    Forms\Components\TextInput::make('mail_subject')
                                        ->label('Oggetto mail di contatto')
                                        ->hintIcon('heroicon-o-tag')
                                        ->hintIconTooltip('general.mail_subject')
                                        ->required()
                                        ->suffixAction(
                                            Action::make('test_email')
                                                ->icon('heroicon-m-envelope')
                                                ->label('Invia email di test')
                                                ->tooltip('Invia una email di test a un indirizzo specificato')
                                                ->color('success')
                                                ->form([
                                                    Forms\Components\TextInput::make('recipient')
                                                        ->label('Email dove inviare il test')
                                                        ->default(auth()->user()->email)
                                                        ->email()
                                                        ->required(),
                                                ])
                                                ->action(function (array $data) {
                                                    Mail::to($data['recipient'])
                                                        ->send(new ContactMail(
                                                            'Nome Cognome',
                                                            'email@finta.com',
                                                            'Questo è un test di verifica del funzionamento della mail'
                                                        ));
                                                }),
                                        )
                                        ->columnSpan(2),

                                ]),
                        ]),

                    Forms\Components\Tabs\Tab::make('Social')
                        ->schema([
                            Forms\Components\Repeater::make('social_profiles')
                                ->label('Profili social')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->live(false, 500)
                                        ->label('Nome del profilo')
                                        ->required(),
                                    Forms\Components\TextInput::make('url')
                                        ->label('Url del profilo')
                                        ->required(),
                                    Forms\Components\FileUpload::make('icon')
                                        ->label('Carica file icona')
                                        ->image()
                                        ->imageEditor()
                                        ->hidden(fn (Get $get) => $get('use_svg'))
                                        ->helperText('Dimensione consigliata: 32x32px, Formato: png')
                                        ->directory('social-icons'),
                                    CodeEditor::make('svg')
                                        ->label('Svg')
                                        ->visible(fn (Get $get) => $get('use_svg'))
                                        ->columnSpanFull()
                                        ->required(),
                                    Forms\Components\Toggle::make('use_svg')
                                        ->live()
                                        ->label(fn (Get $get) => $get('use_svg') ? 'Carica immagine icona' : 'Usa icona SVG')
                                        ->default(false),
                                ])
                                ->collapsed()
                                ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                ->addActionLabel('Aggiungi profilo'),
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

                                    Forms\Components\Textarea::make('cookieconsent.description')
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
                                        ->options(fn (Get $get) => match ($get('cookieconsent.layout')) {
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
                                        ->options(fn (Get $get) => match ($get('cookieconsent.layout')) {
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
                                        ->options(fn (Get $get) => match ($get('cookieconsent.layout')) {
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
                                    fn (Action $action) => $action->requiresConfirmation(),
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
                                            fn (Action $action) => $action->requiresConfirmation(),
                                        )
                                        ->addActionLabel('Aggiungi cookie')
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ]), ])
            ->statePath('data');
    }
}
