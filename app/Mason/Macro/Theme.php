<?php

namespace App\Mason\Macro;

use Awcodes\Palette\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;

class Theme
{
    /**
     * Restituisce le proprietà del componente
     *
     * @return array<string, mixed>
     */
    public static function getProps(): array
    {
        return ['theme' => []];
    }

    /**
     * Restituisce gli argomenti del componente
     *
     * @param  array<string, mixed>  $arguments
     * @return array<string, mixed>
     */
    public static function getArguments($arguments): array
    {
        return [
            'theme' => [
                // Colore di sfondo
                'background_color' => $arguments['theme']['background_color'] ?? 'white',

                // Sfondo multimediale
                'use_bg' => $arguments['theme']['use_bg'] ?? false,
                'is_video' => $arguments['theme']['is_video'] ?? false,
                'video_mp4' => $arguments['theme']['video_mp4'] ?? null,
                'video_webm' => $arguments['theme']['video_webm'] ?? null,
                'image' => $arguments['theme']['image'] ?? null,
                'parallax' => $arguments['theme']['parallax'] ?? false,
                'height' => $arguments['theme']['height'] ?? 'aspect-auto',
                'overlayTransparency' => $arguments['theme']['overlayTransparency'] ?? 'bg-black/50',
                'bgClip' => $arguments['theme']['bgClip'] ?? false,
                'proseInvert' => $arguments['theme']['proseInvert'] ?? false,

                // Dimensioni e spaziatura
                'blockMaxWidth' => $arguments['theme']['blockMaxWidth'] ?? 'max-w-3xl',
                'blockMaxWidthSm' => $arguments['theme']['blockMaxWidthSm'] ?? null,
                'blockMaxWidthMd' => $arguments['theme']['blockMaxWidthMd'] ?? null,
                'blockMaxWidthLg' => $arguments['theme']['blockMaxWidthLg'] ?? null,
                'blockMaxWidthXl' => $arguments['theme']['blockMaxWidthXl'] ?? null,
                'blockMaxWidth2Xl' => $arguments['theme']['blockMaxWidth2xl'] ?? null,
                'blockVerticalPadding' => $arguments['theme']['blockVerticalPadding'] ?? 'py-8',
                'blockVerticalPaddingSm' => $arguments['theme']['blockVerticalPaddingSm'] ?? null,
                'blockVerticalPaddingMd' => $arguments['theme']['blockVerticalPaddingMd'] ?? null,
                'blockVerticalPaddingLg' => $arguments['theme']['blockVerticalPaddingLg'] ?? 'lg:py-24',
                'blockVerticalPaddingXl' => $arguments['theme']['blockVerticalPaddingXl'] ?? null,
                'blockVerticalPadding2Xl' => $arguments['theme']['blockVerticalPadding2xl'] ?? null,
                'blockVerticalMargin' => $arguments['theme']['blockVerticalMargin'] ?? null,
                'blockVerticalMarginSm' => $arguments['theme']['blockVerticalMarginSm'] ?? null,
                'blockVerticalMarginMd' => $arguments['theme']['blockVerticalMarginMd'] ?? null,
                'blockVerticalMarginLg' => $arguments['theme']['blockVerticalMarginLg'] ?? null,
                'blockVerticalMarginXl' => $arguments['theme']['blockVerticalMarginXl'] ?? null,
                'blockVerticalMargin2Xl' => $arguments['theme']['blockVerticalMargin2xl'] ?? null,
            ],
        ];
    }

    /**
     * Restituisce i campi del componente
     *
     * @return Tabs
     */
    public static function getFields()
    {
        return
            Tabs::make('Impostazioni generali')
            // ->icon('phosphor-wrench')
            // ->collapsible(true)
                ->tabs([

                    Tabs\Tab::make('Sfondo')
                        ->schema([
                            ColorPicker::make('theme.background_color')
                                ->label('Colore di sfondo')
                                ->storeAsKey()
                                ->colors(fn (): array => [
                                    // Considera i colori definiti in resources\css\app.css
                                    'white' => 'bg-white',

                                    // Colori personalizzati
                                    'gray' => 'bg-neutral-100',
                                    'accent' => '#e66d39',
                                    'primary' => '#457b9d',
                                    'secondary' => '#1D3557',
                                    'tertiary' => '#A8DADC',
                                    'quaternary' => '#F1FAEE',
                                ]),

                            Toggle::make('theme.use_bg')
                                ->label('Usa un\'immagine o un video come sfondo')
                                ->live()
                                ->default(false),

                            Grid::make('Sfondo del blocco')
                                ->visible(fn (Get $get) => $get('theme.use_bg') === true)
                                ->columns(2)
                                ->schema([

                                    Toggle::make('theme.is_video')
                                        ->live()
                                        ->label('Sfondo video')
                                        ->columnSpanFull()
                                        ->default(false),

                                    FileUpload::make('theme.video_mp4')
                                        ->hidden(fn (Get $get) => $get('theme.is_video') === false)
                                        ->label('Video in formato MP4')
                                        ->directory('bg-videos')
                                        ->required()
                                        ->acceptedFileTypes(['video/mp4']),

                                    FileUpload::make('theme.video_webm')
                                        ->hidden(fn (Get $get) => $get('theme.is_video') === false)
                                        ->label('Video in formato WEBM')
                                        ->directory('bg-videos')
                                        ->acceptedFileTypes(['video/webm']),

                                    FileUpload::make('theme.image')
                                        ->label(fn (Get $get) => $get('theme.is_video') === true ? 'Immagine di fallback' : 'Immagine di sfondo')
                                        ->directory('bg-images')
                                        ->image()
                                        ->imageEditor()
                                        ->columnSpanFull(),

                                    Toggle::make('theme.parallax')
                                        ->hidden(fn (Get $get) => $get('theme.is_video') === true)
                                        ->label('Effetto parallasse')
                                        ->columnSpanFull()
                                        ->default(false),

                                    Select::make('theme.height')
                                        ->label('Altezza')
                                        ->options([
                                            'aspect-auto' => 'Altezza secondo contenuto',
                                            'aspect-video' => 'Aspetto 16:9',
                                            'aspect-[21/9]' => 'Aspetto 21:9',
                                            'aspect-[4/3]' => 'Aspetto 4:3',
                                            'aspect-[3/1]' => 'Aspetto 3:1',
                                            'h-[20vh]' => '20% altezza viewport',
                                            'h-[30vh]' => '30% altezza viewport',
                                            'h-[40vh]' => '40% altezza viewport',
                                            'h-[50vh]' => '50% altezza viewport',
                                            'h-[60vh]' => '60% altezza viewport',
                                            'h-[70vh]' => '70% altezza viewport',
                                            'h-[80vh]' => '80% altezza viewport',
                                            'h-[90vh]' => '90% altezza viewport',
                                            'h-[100vh]' => '100% altezza viewport',
                                        ]),

                                    Select::make('theme.overlayTransparency')
                                        ->label('Trasparenza overlay')
                                        ->options([
                                            'bg-black/10' => '10%',
                                            'bg-black/20' => '20%',
                                            'bg-black/30' => '30%',
                                            'bg-black/40' => '40%',
                                            'bg-black/50' => '50%',
                                            'bg-black/60' => '60%',
                                            'bg-black/70' => '70%',
                                            'bg-black/80' => '80%',
                                            'bg-black/90' => '90%',
                                        ])
                                        ->default('bg-black/50'),

                                    Toggle::make('theme.bgClip')
                                        ->label('Clip background')
                                        ->default(false),

                                    Toggle::make('theme.proseInvert')
                                        ->label('Colore del testo invertito')
                                        ->default(false),
                                ]),
                        ]),

                    Tabs\Tab::make('Dimensioni e spaziatura')
                        ->schema([
                            Fieldset::make('Larghezza del blocco')
                                // ->description('Configura la larghezza massima del blocco per adattarsi a diverse dimensioni di schermo.')
                                ->columns(3)
                                ->schema(array_map(function ($size) {
                                    return Select::make('theme.blockMaxWidth'.($size !== 'xs' ? ucfirst($size) : ''))
                                        ->label(self::responsiveLabel($size))
                                        ->searchable()
                                        ->placeholder($size === 'xs' ? 'Non indicato' : 'Non indicato / Ereditato')
                                        ->options(self::maxWidthOptions($size === 'xs' ? null : $size));
                                    // ->default($size === 'xs' ? 'max-w-7xl' : null);
                                }, ['xs', 'sm', 'md', 'lg', 'xl', '2xl'])),

                            Fieldset::make('Padding verticale del blocco')
                                // ->description('Configura la spaziatura verticale del blocco per ciascun breakpoint.')
                                ->columns(3)
                                ->schema(array_map(function ($size) {
                                    return Select::make('theme.blockVerticalPadding'.($size !== 'xs' ? ucfirst($size) : ''))
                                        ->label(self::responsiveLabel($size))
                                        ->searchable()
                                        ->placeholder($size === 'xs' ? 'Non indicato' : 'Non indicato / Ereditato')
                                        ->options(self::paddingOptions($size === 'xs' ? null : $size));
                                    // ->default(match ($size) {
                                    //     'xs' => 'py-12',
                                    //     'lg' => 'py-24',
                                    //     default => null,
                                    // });
                                }, ['xs', 'sm', 'md', 'lg', 'xl', '2xl'])),

                            Fieldset::make('Margine verticale del blocco')
                                // ->description('Configura la spaziatura verticale del blocco per ciascun breakpoint.')
                                ->columns(3)
                                ->schema(array_map(function ($size) {
                                    return Select::make('theme.blockVerticalMargin'.($size !== 'xs' ? ucfirst($size) : ''))
                                        ->label(self::responsiveLabel($size))
                                        ->searchable()
                                        ->placeholder($size === 'xs' ? 'Non indicato' : 'Non indicato / Ereditato')
                                        ->options(self::marginOptions($size === 'xs' ? null : $size));
                                }, ['xs', 'sm', 'md', 'lg', 'xl', '2xl'])),
                        ]),

                ]);
    }

    /**
     * Restituisce le opzioni di larghezza massima per il blocco
     *
     * @param  string|null  $screen
     * @return array<string, string>
     */
    public static function maxWidthOptions($screen = null): array
    {
        $prefix = ($screen && $screen !== 'xs') ? $screen.':' : '';

        $options = [];

        if ($screen && $screen !== 'xs') {
            $options[$prefix.'max-w-inherited'] = 'Eredita';
        }

        $options += [
            $prefix.'max-w-none' => 'nessuna',
            $prefix.'max-w-xs' => '320px (xs)',
            $prefix.'max-w-sm' => '384px (sm)',
            $prefix.'max-w-md' => '448px (md)',
            $prefix.'max-w-lg' => '512px (lg)',
            $prefix.'max-w-xl' => '576px (xl)',
            $prefix.'max-w-2xl' => '672px (2xl)',
            $prefix.'max-w-3xl' => '768px (3xl)',
            $prefix.'max-w-4xl' => '896px (4xl)',
            $prefix.'max-w-5xl' => '1024px (5xl)',
            $prefix.'max-w-6xl' => '1152px (6xl)',
            $prefix.'max-w-7xl' => '1280px (7xl)',
            $prefix.'max-w-screen-2xl' => '1536px',
            $prefix.'max-w-full' => '100%',
            $prefix.'max-w-min' => 'contenuto minimo',
            $prefix.'max-w-max' => 'contenuto massimo',
            $prefix.'max-w-fit' => 'adattato',
        ];

        return $options;

        // Tailwind CSS maxWidth classes (elencate tutte per permettere a tailwind di salvarle nel css)
        // max-w-none max-w-xs max-w-sm max-w-md max-w-lg max-w-xl max-w-2xl max-w-3xl max-w-4xl max-w-5xl max-w-6xl max-w-7xl max-w-full max-w-min max-w-max max-w-fit max-w-screen-2xl
        // sm:max-w-none sm:max-w-xs sm:max-w-sm sm:max-w-md sm:max-w-lg sm:max-w-xl sm:max-w-2xl sm:max-w-3xl sm:max-w-4xl sm:max-w-5xl sm:max-w-6xl sm:max-w-7xl sm:max-w-full sm:max-w-min sm:max-w-max sm:max-w-fit sm:max-w-screen-2xl
        // md:max-w-none md:max-w-xs md:max-w-sm md:max-w-md md:max-w-lg md:max-w-xl md:max-w-2xl md:max-w-3xl md:max-w-4xl md:max-w-5xl md:max-w-6xl md:max-w-7xl md:max-w-full md:max-w-min md:max-w-max md:max-w-fit md:max-w-screen-2xl
        // lg:max-w-none lg:max-w-xs lg:max-w-sm lg:max-w-md lg:max-w-lg lg:max-w-xl lg:max-w-2xl lg:max-w-3xl lg:max-w-4xl lg:max-w-5xl lg:max-w-6xl lg:max-w-7xl lg:max-w-full lg:max-w-min lg:max-w-max lg:max-w-fit lg:max-w-screen-2xl
        // xl:max-w-none xl:max-w-xs xl:max-w-sm xl:max-w-md xl:max-w-lg xl:max-w-xl xl:max-w-2xl xl:max-w-3xl xl:max-w-4xl xl:max-w-5xl xl:max-w-6xl xl:max-w-7xl xl:max-w-full xl:max-w-min xl:max-w-max xl:max-w-fit xl:max-w-screen-2xl
        // 2xl:max-w-none 2xl:max-w-xs 2xl:max-w-sm 2xl:max-w-md 2xl:max-w-lg 2xl:max-w-xl 2xl:max-w-2xl 2xl:max-w-3xl 2xl:max-w-4xl 2xl:max-w-5xl 2xl:max-w-6xl 2xl:max-w-7xl 2xl:max-w-full 2xl:max-w-min 2xl:max-w-max 2xl:max-w-fit 2xl:max-w-screen-2xl
    }

    /**
     * Restituisce l'etichetta per i breakpoint responsivi
     *
     * @param  mixed  $screen
     * @return array<string, string>
     */
    public static function columnOptions($screen = null): array
    {
        $prefix = ($screen && $screen !== 'xs') ? $screen.':' : '';

        return [
            $prefix.'columns-1' => '1 colonna',
            $prefix.'columns-2' => '2 colonne',
            $prefix.'columns-3' => '3 colonne',
            $prefix.'columns-4' => '4 colonne',
            $prefix.'columns-5' => '5 colonne',
            $prefix.'columns-6' => '6 colonne',
            $prefix.'columns-7' => '7 colonne',
            $prefix.'columns-8' => '8 colonne',
            $prefix.'columns-9' => '9 colonne',
            $prefix.'columns-10' => '10 colonne',
            $prefix.'columns-11' => '11 colonne',
            $prefix.'columns-12' => '12 colonne',
        ];

        // Tailwind CSS column classes (elencate tutte per permettere a tailwind di salvarle nel css)
        // columns-1 columns-2 columns-3 columns-4 columns-5 columns-6 columns-7 columns-8 columns-9 columns-10 columns-11 columns-12
        // sm:columns-1 sm:columns-2 sm:columns-3 sm:columns-4 sm:columns-5 sm:columns-6 sm:columns-7 sm:columns-8 sm:columns-9 sm:columns-10 sm:columns-11 sm:columns-12
        // md:columns-1 md:columns-2 md:columns-3 md:columns-4 md:columns-5 md:columns-6 md:columns-7 md:columns-8 md:columns-9 md:columns-10 md:columns-11 md:columns-12
        // lg:columns-1 lg:columns-2 lg:columns-3 lg:columns-4 lg:columns-5 lg:columns-6 lg:columns-7 lg:columns-8 lg:columns-9 lg:columns-10 lg:columns-11 lg:columns-12
        // xl:columns-1 xl:columns-2 xl:columns-3 xl:columns-4 xl:columns-5 xl:columns-6 xl:columns-7 xl:columns-8 xl:columns-9 xl:columns-10 xl:columns-11 xl:columns-12
        // 2xl:columns-1 2xl:columns-2 2xl:columns-3 2xl:columns-4 2xl:columns-5 2xl:columns-6 2xl:columns-7 2xl:columns-8 2xl:columns-9 2xl:columns-10 2xl:columns-11 2xl:columns-12
    }

    /**
     * Restituisce l'etichetta per i breakpoint responsivi
     *
     * @param  string|null  $screen
     * @return array<string, string>
     */
    public static function paddingOptions($screen = null): array
    {
        $prefix = ($screen && $screen !== 'xs') ? $screen.':' : '';

        return [
            $prefix.'py-inherited' => 'Eredita',
            $prefix.'py-0' => 'Nessuna ('.($prefix.'py-0').')',
            $prefix.'py-1' => ($prefix.'py-1').' (0.25rem / 4px)',
            $prefix.'py-2' => ($prefix.'py-2').' (0.5rem / 8px)',
            $prefix.'py-3' => ($prefix.'py-3').' (0.75rem / 12px)',
            $prefix.'py-4' => ($prefix.'py-4').' (1rem / 16px)',
            $prefix.'py-5' => ($prefix.'py-5').' (1.25rem / 20px)',
            $prefix.'py-6' => ($prefix.'py-6').' (1.5rem / 24px)',
            $prefix.'py-8' => ($prefix.'py-8').' (2rem / 32px)',
            $prefix.'py-10' => ($prefix.'py-10').' (2.5rem / 40px)',
            $prefix.'py-12' => ($prefix.'py-12').' (3rem / 48px)',
            $prefix.'py-16' => ($prefix.'py-16').' (4rem / 64px)',
            $prefix.'py-20' => ($prefix.'py-20').' (5rem / 80px)',
            $prefix.'py-24' => ($prefix.'py-24').' (6rem / 96px)',
            $prefix.'py-32' => ($prefix.'py-32').' (8rem / 128px)',
            $prefix.'py-40' => ($prefix.'py-40').' (10rem / 160px)',
            $prefix.'py-48' => ($prefix.'py-48').' (12rem / 192px)',
            $prefix.'py-56' => ($prefix.'py-56').' (14rem / 224px)',
            $prefix.'py-64' => ($prefix.'py-64').' (16rem / 256px)',
        ];

        // Tailwind CSS padding classes (elencate tutte per permettere a tailwind di salvarle nel css)
        // py-0 py-1 py-2 py-3 py-4 py-5 py-6 py-8 py-10 py-12 py-16 py-20 py-24 py-32 py-40 py-48 py-56 py-64
        // sm:py-0 sm:py-1 sm:py-2 sm:py-3 sm:py-4 sm:py-5 sm:py-6 sm:py-8 sm:py-10 sm:py-12 sm:py-16 sm:py-20 sm:py-24 sm:py-32 sm:py-40 sm:py-48 sm:py-56 sm:py-64
        // md:py-0 md:py-1 md:py-2 md:py-3 md:py-4 md:py-5 md:py-6 md:py-8 md:py-10 md:py-12 md:py-16 md:py-20 md:py-24 md:py-32 md:py-40 md:py-48 md:py-56 md:py-64
        // lg:py-0 lg:py-1 lg:py-2 lg:py-3 lg:py-4 lg:py-5 lg:py-6 lg:py-8 lg:py-10 lg:py-12 lg:py-16 lg:py-20 lg:py-24 lg:py-32 lg:py-40 lg:py-48 lg:py-56 lg:py-64
        // xl:py-0 xl:py-1 xl:py-2 xl:py-3 xl:py-4 xl:py-5 xl:py-6 xl:py-8 xl:py-10 xl:py-12 xl:py-16 xl:py-20 xl:py-24 xl:py-32 xl:py-40 xl:py-48 xl:py-56 xl:py-64
        // 2xl:py-0 2xl:py-1 2xl:py-2 2xl:py-3 2xl:py-4 2xl:py-5 2xl:py-6 2xl:py-8 2xl:py-10 2xl:py-12 2xl:py-16 2xl:py-20 2xl:py-24 2xl:py-32 2xl:py-40 2xl:py-48 2xl:py-56 2xl:py-64
    }

    /**
     * Restituisce l'etichetta per i breakpoint responsivi
     *
     * @param  string|null  $screen
     * @return array<string, string>
     */
    public static function marginOptions($screen = null): array
    {
        $prefix = ($screen && $screen !== 'xs') ? $screen.':' : '';

        return [
            $prefix.'my-inherited' => 'Eredita',
            $prefix.'my-0' => 'Nessuno ('.($prefix.'my-0').')',
            $prefix.'my-1' => ($prefix.'my-1').' (0.25rem / 4px)',
            $prefix.'my-2' => ($prefix.'my-2').' (0.5rem / 8px)',
            $prefix.'my-3' => ($prefix.'my-3').' (0.75rem / 12px)',
            $prefix.'my-4' => ($prefix.'my-4').' (1rem / 16px)',
            $prefix.'my-5' => ($prefix.'my-5').' (1.25rem / 20px)',
            $prefix.'my-6' => ($prefix.'my-6').' (1.5rem / 24px)',
            $prefix.'my-8' => ($prefix.'my-8').' (2rem / 32px)',
            $prefix.'my-10' => ($prefix.'my-10').' (2.5rem / 40px)',
            $prefix.'my-12' => ($prefix.'my-12').' (3rem / 48px)',
            $prefix.'my-16' => ($prefix.'my-16').' (4rem / 64px)',
            $prefix.'my-20' => ($prefix.'my-20').' (5rem / 80px)',
            $prefix.'my-24' => ($prefix.'my-24').' (6rem / 96px)',
            $prefix.'my-32' => ($prefix.'my-32').' (8rem / 128px)',
            $prefix.'my-40' => ($prefix.'my-40').' (10rem / 160px)',
            $prefix.'my-48' => ($prefix.'my-48').' (12rem / 192px)',
            $prefix.'my-56' => ($prefix.'my-56').' (14rem / 224px)',
            $prefix.'my-64' => ($prefix.'my-64').' (16rem / 256px)',
        ];

        // Tailwind CSS margin classes (elencate tutte per permettere a tailwind di salvarle nel css)
        // my-0 my-1 my-2 my-3 my-4 my-5 my-6 my-8 my-10 my-12 my-16 my-20 my-24 my-32 my-40 my-48 my-56 my-64
        // sm:my-0 sm:my-1 sm:my-2 sm:my-3 sm:my-4 sm:my-5 sm:my-6 sm:my-8 sm:my-10 sm:my-12 sm:my-16 sm:my-20 sm:my-24 sm:my-32 sm:my-40 sm:my-48 sm:my-56 sm:my-64
        // md:my-0 md:my-1 md:my-2 md:my-3 md:my-4 md:my-5 md:my-6 md:my-8 md:my-10 md:my-12 md:my-16 md:my-20 md:my-24 md:my-32 md:my-40 md:my-48 md:my-56 md:my-64
        // lg:my-0 lg:my-1 lg:my-2 lg:my-3 lg:my-4 lg:my-5 lg:my-6 lg:my-8 lg:my-10 lg:my-12 lg:my-16 lg:my-20 lg:my-24 lg:my-32 lg:my-40 lg:my-48 lg:my-56 lg:my-64
        // xl:my-0 xl:my-1 xl:my-2 xl:my-3 xl:my-4 xl:my-5 xl:my-6 xl:my-8 xl:my-10 xl:my-12 xl:my-16 xl:my-20 xl:my-24 xl:my-32 xl:my-40 xl:my-48 xl:my-56 xl:my-64
        // 2xl:my-0 2xl:my-1 2xl:my-2 2xl:my-3 2xl:my-4 2xl:my-5 2xl:my-6 2xl:my-8 2xl:my-10 2xl:my-12 2xl:my-16 2xl:my-20 2xl:my-24 2xl:my-32 2xl:my-40 2xl:my-48 2xl:my-56 2xl:my-64
    }

    /**
     * Restituisce l'etichetta per i breakpoint responsivi
     *
     * @param  string|null  $size
     */
    public static function responsiveLabel($size = null): string
    {
        if (is_null($size)) {
            $size = 'xs';
        }

        return match ($size) {
            'xs' => 'Smartphone',
            'sm' => 'Smartphone orizzontale',
            'md' => 'Tablet / piccolo laptop',
            'lg' => 'Laptop / piccolo desktop',
            'xl' => 'Desktop',
            '2xl' => 'Desktop grande',
            default => 'Non indicato',
        };
    }
}
