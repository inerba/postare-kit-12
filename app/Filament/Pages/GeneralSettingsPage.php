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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use Postare\DbConfig\AbstractPageSettings;
use Postare\DbConfig\DbConfig;

class GeneralSettingsPage extends AbstractPageSettings
{
    use HasPageShield, HasSeoFields;

    /**
     * @var array<string, mixed>|null
     */
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
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64h98.2V334.2H109.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H255V480H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"/></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'Instagram',
                'url' => 'https://www.instagram.com/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512"><path d="M194.4 211.7a53.3 53.3 0 1 0 59.3 88.7 53.3 53.3 0 1 0 -59.3-88.7zm142.3-68.4c-5.2-5.2-11.5-9.3-18.4-12c-18.1-7.1-57.6-6.8-83.1-6.5c-4.1 0-7.9 .1-11.2 .1c-3.3 0-7.2 0-11.4-.1c-25.5-.3-64.8-.7-82.9 6.5c-6.9 2.7-13.1 6.8-18.4 12s-9.3 11.5-12 18.4c-7.1 18.1-6.7 57.7-6.5 83.2c0 4.1 .1 7.9 .1 11.1s0 7-.1 11.1c-.2 25.5-.6 65.1 6.5 83.2c2.7 6.9 6.8 13.1 12 18.4s11.5 9.3 18.4 12c18.1 7.1 57.6 6.8 83.1 6.5c4.1 0 7.9-.1 11.2-.1c3.3 0 7.2 0 11.4 .1c25.5 .3 64.8 .7 82.9-6.5c6.9-2.7 13.1-6.8 18.4-12s9.3-11.5 12-18.4c7.2-18 6.8-57.4 6.5-83c0-4.2-.1-8.1-.1-11.4s0-7.1 .1-11.4c.3-25.5 .7-64.9-6.5-83l0 0c-2.7-6.9-6.8-13.1-12-18.4zm-67.1 44.5A82 82 0 1 1 178.4 324.2a82 82 0 1 1 91.1-136.4zm29.2-1.3c-3.1-2.1-5.6-5.1-7.1-8.6s-1.8-7.3-1.1-11.1s2.6-7.1 5.2-9.8s6.1-4.5 9.8-5.2s7.6-.4 11.1 1.1s6.5 3.9 8.6 7s3.2 6.8 3.2 10.6c0 2.5-.5 5-1.4 7.3s-2.4 4.4-4.1 6.2s-3.9 3.2-6.2 4.2s-4.8 1.5-7.3 1.5l0 0c-3.8 0-7.5-1.1-10.6-3.2zM448 96c0-35.3-28.7-64-64-64H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96zM357 389c-18.7 18.7-41.4 24.6-67 25.9c-26.4 1.5-105.6 1.5-132 0c-25.6-1.3-48.3-7.2-67-25.9s-24.6-41.4-25.8-67c-1.5-26.4-1.5-105.6 0-132c1.3-25.6 7.1-48.3 25.8-67s41.5-24.6 67-25.8c26.4-1.5 105.6-1.5 132 0c25.6 1.3 48.3 7.1 67 25.8s24.6 41.4 25.8 67c1.5 26.3 1.5 105.4 0 131.9c-1.3 25.6-7.1 48.3-25.8 67z"/></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'X',
                'url' => 'https://twitter.com/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm297.1 84L257.3 234.6 379.4 396H283.8L209 298.1 123.3 396H75.8l111-126.9L69.7 116h98l67.7 89.5L313.6 116h47.5zM323.3 367.6L153.4 142.9H125.1L296.9 367.6h26.3z"/></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'Threads',
                'url' => 'https://www.threads.net/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zM294.2 244.3c19.5 9.3 33.7 23.5 41.2 40.9c10.4 24.3 11.4 63.9-20.2 95.4c-24.2 24.1-53.5 35-95.1 35.3h-.2c-46.8-.3-82.8-16.1-106.9-46.8C91.5 341.8 80.4 303.7 80 256v-.1-.1c.4-47.7 11.5-85.7 33-113.1c24.2-30.7 60.2-46.5 106.9-46.8h.2c46.9 .3 83.3 16 108.2 46.6c12.3 15.1 21.3 33.3 27 54.4l-26.9 7.2c-4.7-17.2-11.9-31.9-21.4-43.6c-19.4-23.9-48.7-36.1-87-36.4c-38 .3-66.8 12.5-85.5 36.2c-17.5 22.3-26.6 54.4-26.9 95.5c.3 41.1 9.4 73.3 26.9 95.5c18.7 23.8 47.4 36 85.5 36.2c34.3-.3 56.9-8.4 75.8-27.3c21.5-21.5 21.1-47.9 14.2-64c-4-9.4-11.4-17.3-21.3-23.3c-2.4 18-7.9 32.2-16.5 43.2c-11.4 14.5-27.7 22.4-48.4 23.5c-15.7 .9-30.8-2.9-42.6-10.7c-13.9-9.2-22-23.2-22.9-39.5c-1.7-32.2 23.8-55.3 63.5-57.6c14.1-.8 27.3-.2 39.5 1.9c-1.6-9.9-4.9-17.7-9.8-23.4c-6.7-7.8-17.1-11.8-30.8-11.9h-.4c-11 0-26 3.1-35.6 17.6l-23-15.8c12.8-19.4 33.6-30.1 58.5-30.1h.6c41.8 .3 66.6 26.3 69.1 71.8c1.4 .6 2.8 1.2 4.2 1.9l.1 .5zm-71.8 67.5c17-.9 36.4-7.6 39.7-48.8c-8.8-1.9-18.6-2.9-29-2.9c-3.2 0-6.4 .1-9.6 .3c-28.6 1.6-38.1 15.5-37.4 27.9c.9 16.7 19 24.5 36.4 23.6l-.1-.1z"/></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'GitHub',
                'url' => 'https://github.com/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512"><path d="M448 96c0-35.3-28.7-64-64-64H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96zM265.8 407.7c0-1.8 0-6 .1-11.6c.1-11.4 .1-28.8 .1-43.7c0-15.6-5.2-25.5-11.3-30.7c37-4.1 76-9.2 76-73.1c0-18.2-6.5-27.3-17.1-39c1.7-4.3 7.4-22-1.7-45c-13.9-4.3-45.7 17.9-45.7 17.9c-13.2-3.7-27.5-5.6-41.6-5.6s-28.4 1.9-41.6 5.6c0 0-31.8-22.2-45.7-17.9c-9.1 22.9-3.5 40.6-1.7 45c-10.6 11.7-15.6 20.8-15.6 39c0 63.6 37.3 69 74.3 73.1c-4.8 4.3-9.1 11.7-10.6 22.3c-9.5 4.3-33.8 11.7-48.3-13.9c-9.1-15.8-25.5-17.1-25.5-17.1c-16.2-.2-1.1 10.2-1.1 10.2c10.8 5 18.4 24.2 18.4 24.2c9.7 29.7 56.1 19.7 56.1 19.7c0 9 .1 21.7 .1 30.6c0 4.8 .1 8.6 .1 10c0 4.3-3 9.5-11.5 8C106 393.6 59.8 330.8 59.8 257.4c0-91.8 70.2-161.5 162-161.5s166.2 69.7 166.2 161.5c.1 73.4-44.7 136.3-110.7 158.3c-8.4 1.5-11.5-3.7-11.5-8zm-90.5-54.8c-.2-1.5 1.1-2.8 3-3.2c1.9-.2 3.7 .6 3.9 1.9c.3 1.3-1 2.6-3 3c-1.9 .4-3.7-.4-3.9-1.7zm-9.1 3.2c-2.2 .2-3.7-.9-3.7-2.4c0-1.3 1.5-2.4 3.5-2.4c1.9-.2 3.7 .9 3.7 2.4c0 1.3-1.5 2.4-3.5 2.4zm-14.3-2.2c-1.9-.4-3.2-1.9-2.8-3.2s2.4-1.9 4.1-1.5c2 .6 3.3 2.1 2.8 3.4c-.4 1.3-2.4 1.9-4.1 1.3zm-12.5-7.3c-1.5-1.3-1.9-3.2-.9-4.1c.9-1.1 2.8-.9 4.3 .6c1.3 1.3 1.8 3.3 .9 4.1c-.9 1.1-2.8 .9-4.3-.6zm-8.5-10c-1.1-1.5-1.1-3.2 0-3.9c1.1-.9 2.8-.2 3.7 1.3c1.1 1.5 1.1 3.3 0 4.1c-.9 .6-2.6 0-3.7-1.5zm-6.3-8.8c-1.1-1.3-1.3-2.8-.4-3.5c.9-.9 2.4-.4 3.5 .6c1.1 1.3 1.3 2.8 .4 3.5c-.9 .9-2.4 .4-3.5-.6zm-6-6.4c-1.3-.6-1.9-1.7-1.5-2.6c.4-.6 1.5-.9 2.8-.4c1.3 .7 1.9 1.8 1.5 2.6c-.4 .9-1.7 1.1-2.8 .4z"/></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'WhatsApp',
                'url' => 'https://wa.me/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512"><path d="M92.1 254.6c0 24.9 7 49.2 20.2 70.1l3.1 5-13.3 48.6L152 365.2l4.8 2.9c20.2 12 43.4 18.4 67.1 18.4h.1c72.6 0 133.3-59.1 133.3-131.8c0-35.2-15.2-68.3-40.1-93.2c-25-25-58-38.7-93.2-38.7c-72.7 0-131.8 59.1-131.9 131.8zM274.8 330c-12.6 1.9-22.4 .9-47.5-9.9c-36.8-15.9-61.8-51.5-66.9-58.7c-.4-.6-.7-.9-.8-1.1c-2-2.6-16.2-21.5-16.2-41c0-18.4 9-27.9 13.2-32.3c.3-.3 .5-.5 .7-.8c3.6-4 7.9-5 10.6-5c2.6 0 5.3 0 7.6 .1c.3 0 .5 0 .8 0c2.3 0 5.2 0 8.1 6.8c1.2 2.9 3 7.3 4.9 11.8c3.3 8 6.7 16.3 7.3 17.6c1 2 1.7 4.3 .3 6.9c-3.4 6.8-6.9 10.4-9.3 13c-3.1 3.2-4.5 4.7-2.3 8.6c15.3 26.3 30.6 35.4 53.9 47.1c4 2 6.3 1.7 8.6-1c2.3-2.6 9.9-11.6 12.5-15.5c2.6-4 5.3-3.3 8.9-2s23.1 10.9 27.1 12.9c.8 .4 1.5 .7 2.1 1c2.8 1.4 4.7 2.3 5.5 3.6c.9 1.9 .9 9.9-2.4 19.1c-3.3 9.3-19.1 17.7-26.7 18.8zM448 96c0-35.3-28.7-64-64-64H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96zM148.1 393.9L64 416l22.5-82.2c-13.9-24-21.2-51.3-21.2-79.3C65.4 167.1 136.5 96 223.9 96c42.4 0 82.2 16.5 112.2 46.5c29.9 30 47.9 69.8 47.9 112.2c0 87.4-72.7 158.5-160.1 158.5c-26.6 0-52.7-6.7-75.8-19.3z"/></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'YouTube',
                'url' => 'https://www.youtube.com/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512"><path d="M282 256.2l-95.2-54.1V310.3L282 256.2zM384 32H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64zm14.4 136.1c7.6 28.6 7.6 88.2 7.6 88.2s0 59.6-7.6 88.1c-4.2 15.8-16.5 27.7-32.2 31.9C337.9 384 224 384 224 384s-113.9 0-142.2-7.6c-15.7-4.2-28-16.1-32.2-31.9C42 315.9 42 256.3 42 256.3s0-59.7 7.6-88.2c4.2-15.8 16.5-28.2 32.2-32.4C110.1 128 224 128 224 128s113.9 0 142.2 7.7c15.7 4.2 28 16.6 32.2 32.4z"/></svg>',
                'use_svg' => true,
            ],
            [
                'title' => 'LinkedIn',
                'url' => 'https://www.linkedin.com/',
                'icon' => null,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>',
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

        /* @phpstan-ignore-next-line */
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        $user = Auth::user();

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
                                                        ->default($user->email)
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
                                        ->content(fn (Get $get) => $get('use_svg') ? new HtmlString("<div class=\"text-black dark:text-gray-100 \">{$get('svg')}</div>") : '<img src="'.$get('icon').'" alt="Anteprima icona" />')
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
