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
use Illuminate\Support\HtmlString;
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

        $socialProfiles = $this->data['social_profiles'] ?? [
            [
                'title' => 'Facebook',
                'url' => 'https://www.facebook.com/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M232,128a104.16,104.16,0,0,1-91.55,103.26,4,4,0,0,1-4.45-4V152h24a8,8,0,0,0,8-8.53,8.17,8.17,0,0,0-8.25-7.47H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,8-8.53A8.17,8.17,0,0,0,167.73,80H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0-8,8.53A8.17,8.17,0,0,0,96.27,152H120v75.28a4,4,0,0,1-4.44,4A104.15,104.15,0,0,1,24.07,124.09c2-54,45.74-97.9,99.78-100A104.12,104.12,0,0,1,232,128Z"></path></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'Instagram',
                'url' => 'https://www.instagram.com/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M176,24H80A56.06,56.06,0,0,0,24,80v96a56.06,56.06,0,0,0,56,56h96a56.06,56.06,0,0,0,56-56V80A56.06,56.06,0,0,0,176,24ZM128,176a48,48,0,1,1,48-48A48.05,48.05,0,0,1,128,176Zm60-96a12,12,0,1,1,12-12A12,12,0,0,1,188,80Zm-28,48a32,32,0,1,1-32-32A32,32,0,0,1,160,128Z"></path></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'X',
                'url' => 'https://twitter.com/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M215,219.85a8,8,0,0,1-7,4.15H160a8,8,0,0,1-6.75-3.71l-40.49-63.63L53.92,221.38a8,8,0,0,1-11.84-10.76l61.77-68L41.25,44.3A8,8,0,0,1,48,32H96a8,8,0,0,1,6.75,3.71l40.49,63.63,58.84-64.72a8,8,0,0,1,11.84,10.76l-61.77,67.95,62.6,98.38A8,8,0,0,1,215,219.85Z"></path></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'Threads',
                'url' => 'https://www.threads.net/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M138.62,128a53.54,53.54,0,0,1,13.1,1.63c-.57,8.21-3.34,15-8.11,19.61A23.89,23.89,0,0,1,127,156c-11.87,0-15-7.58-15-12.07C112,133,125.8,128,138.62,128ZM224,128c0,65.12-35.89,104-96,104S32,193.12,32,128,67.89,24,128,24,224,62.88,224,128ZM72,128c0-43.07,18.32-64,56-64,26.34,0,43,10.08,50.81,30.83a8,8,0,0,0,15-5.66C180.9,55.14,150.9,48,128,48c-26.1,0-45.52,8.7-57.72,25.86C60.8,87.19,56,105.4,56,128s4.8,40.81,14.28,54.14C82.48,199.3,101.9,208,128,208c24.45,0,39.82-8.8,48.41-16.18,10.76-9.25,17.19-21.89,17.19-33.82,0-14.3-6.59-26.79-18.56-35.17a54.16,54.16,0,0,0-7.77-4.5c-2.09-14.65-10-25.75-22.34-31.07C130.43,81,112,83.93,101.21,94.19a8,8,0,0,0,11,11.62c5.43-5.14,16.79-8,26.4-3.85a20.05,20.05,0,0,1,10.77,10.92,68.89,68.89,0,0,0-10.76-.85C113.53,112,96,125.15,96,143.93c0,16.27,13,28.07,31,28.07a40,40,0,0,0,27.75-11.29c4.7-4.59,10.11-12.2,12.17-24A25.55,25.55,0,0,1,177.6,158c0,13.71-15.76,34-49.6,34C90.32,192,72,171.07,72,128Z"></path></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'GitHub',
                'url' => 'https://github.com/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M216,104v8a56.06,56.06,0,0,1-48.44,55.47A39.8,39.8,0,0,1,176,192v40a8,8,0,0,1-8,8H104a8,8,0,0,1-8-8V216H72a40,40,0,0,1-40-40A24,24,0,0,0,8,152a8,8,0,0,1,0-16,40,40,0,0,1,40,40,24,24,0,0,0,24,24H96v-8a39.8,39.8,0,0,1,8.44-24.53A56.06,56.06,0,0,1,56,112v-8a58.14,58.14,0,0,1,7.69-28.32A59.78,59.78,0,0,1,69.07,28,8,8,0,0,1,76,24a59.75,59.75,0,0,1,48,24h24a59.75,59.75,0,0,1,48-24,8,8,0,0,1,6.93,4,59.74,59.74,0,0,1,5.37,47.68A58,58,0,0,1,216,104Z"></path></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'WhatsApp',
                'url' => 'https://wa.me/',
                'icon' => null,
                'svg' => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24 48C37.2548 48 48 37.2548 48 24C48 10.7452 37.2548 0 24 0C10.7452 0 0 10.7452 0 24C0 37.2548 10.7452 48 24 48Z" fill="#000000"/><path fill-rule="evenodd" clip-rule="evenodd" d="M24.7911 37.3525H24.7852C22.3967 37.3517 20.0498 36.7524 17.9653 35.6154L10.4 37.6L12.4246 30.2048C11.1757 28.0405 10.5186 25.5855 10.5196 23.0702C10.5228 15.2017 16.9248 8.79999 24.7909 8.79999C28.6086 8.80164 32.1918 10.2879 34.8862 12.9854C37.5806 15.6828 39.0636 19.2683 39.0621 23.0815C39.059 30.9483 32.6595 37.3493 24.7911 37.3525ZM18.3159 33.0319L18.749 33.2889C20.5702 34.3697 22.6578 34.9415 24.7863 34.9423H24.7911C31.3288 34.9423 36.6499 29.6211 36.6525 23.0807C36.6538 19.9112 35.4212 16.9311 33.1817 14.689C30.9422 12.4469 27.964 11.2115 24.7957 11.2104C18.2529 11.2104 12.9318 16.5311 12.9292 23.0711C12.9283 25.3124 13.5554 27.4951 14.7427 29.3836L15.0248 29.8324L13.8265 34.2095L18.3159 33.0319ZM31.4924 26.154C31.7411 26.2742 31.9091 26.3554 31.9808 26.4751C32.0699 26.6238 32.0699 27.3378 31.7729 28.1708C31.4756 29.0038 30.051 29.764 29.3659 29.8663C28.7516 29.9582 27.9741 29.9965 27.1199 29.725C26.602 29.5607 25.9379 29.3413 25.0871 28.9739C21.7442 27.5304 19.485 24.2904 19.058 23.678C19.0281 23.6351 19.0072 23.6051 18.9955 23.5895L18.9927 23.5857C18.804 23.3339 17.5395 21.6468 17.5395 19.9008C17.5395 18.2582 18.3463 17.3973 18.7177 17.001C18.7432 16.9739 18.7666 16.9489 18.7875 16.926C19.1144 16.569 19.5007 16.4797 19.7384 16.4797C19.9761 16.4797 20.2141 16.4819 20.4219 16.4924C20.4475 16.4937 20.4742 16.4935 20.5017 16.4933C20.7095 16.4921 20.9686 16.4906 21.2242 17.1045C21.3225 17.3407 21.4664 17.691 21.6181 18.0604C21.9249 18.8074 22.264 19.6328 22.3236 19.7522C22.4128 19.9307 22.4722 20.1389 22.3533 20.3769C22.3355 20.4126 22.319 20.4463 22.3032 20.4785C22.2139 20.6608 22.1483 20.7948 21.9967 20.9718C21.9372 21.0413 21.8756 21.1163 21.814 21.1913C21.6913 21.3407 21.5687 21.4901 21.4619 21.5965C21.2833 21.7743 21.0975 21.9672 21.3055 22.3242C21.5135 22.6812 22.2292 23.8489 23.2892 24.7945C24.4288 25.8109 25.4192 26.2405 25.9212 26.4582C26.0192 26.5008 26.0986 26.5352 26.1569 26.5644C26.5133 26.7429 26.7213 26.713 26.9294 26.4751C27.1374 26.2371 27.8208 25.4338 28.0584 25.0769C28.2961 24.7201 28.5339 24.7795 28.8607 24.8984C29.1877 25.0176 30.9408 25.8801 31.2974 26.0586C31.367 26.0934 31.4321 26.1249 31.4924 26.154Z" fill="#FDFDFD"/></svg>',
                'use_svg' => true,
            ],
        ];

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
            'social_profiles' => $socialProfiles,
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
                                ->columns(6)
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->live(false, 500)
                                        ->label('Nome del profilo')
                                        ->columnSpan(3)
                                        ->required(),
                                    Forms\Components\TextInput::make('url')
                                        ->label('Url del profilo')
                                        ->columnSpan(3)
                                        ->required(),
                                    Forms\Components\FileUpload::make('icon')
                                        ->label('Carica file icona')
                                        ->columnSpanFull()
                                        ->image()
                                        ->imageEditor()
                                        ->hidden(fn (Get $get) => $get('use_svg'))
                                        ->helperText('Dimensione consigliata: 32x32px, Formato: png')
                                        ->directory('social-icons'),
                                    CodeEditor::make('svg')
                                        ->label('Svg')
                                        // ->live(true)
                                        ->visible(fn (Get $get) => $get('use_svg'))
                                        ->columnSpan(5)
                                        ->required(),
                                    Forms\Components\Placeholder::make('preview')
                                        ->label('Anteprima')
                                        ->content(fn (Get $get) => $get('use_svg') ? new HtmlString($get('svg')) : '<img src="'.$get('icon').'" alt="Anteprima icona" />')
                                        ->visible(fn (Get $get) => $get('use_svg') || $get('icon')),
                                    Forms\Components\Toggle::make('use_svg')
                                        ->live()
                                        ->columnSpanFull()
                                        ->label('Usa icona SVG')
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
