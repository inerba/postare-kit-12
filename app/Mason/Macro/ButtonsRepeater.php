<?php

namespace App\Mason\Macro;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;

/**
 * ButtonsRepeater
 *
 * Componente per la creazione di un ripetitore di pulsanti
 *
 * Lo stile dipende dalla vista blade resources\views\components\mason\buttons.blade.php
 */
class ButtonsRepeater
{
    public static function getProps(): array
    {
        return [
            'buttons' => [],
        ];
    }

    public static function getArguments($arguments): array
    {
        return [
            'buttons' => $arguments['buttons'] ?? [],
        ];
    }

    public static function getFields()
    {
        return Repeater::make('buttons')
            ->label('Pulsanti')
            ->hint('Aggiungi uno o più pulsanti')
            ->columns(2)
            ->minItems(0)
            ->defaultItems(0)
            ->schema([
                TextInput::make('button_text')
                    ->live(false, 500)
                    ->label('Testo del pulsante')
                    ->required(),
                Select::make('button_target')
                    ->label('Target del link')
                    ->options([
                        '_self' => 'Stessa finestra',
                        '_blank' => 'Nuova finestra',
                    ])
                    ->default('_self')
                    ->required(),
                TextInput::make('button_link')
                    ->label('Link del pulsante')
                    ->columnSpanFull()
                    ->required(),
                Select::make('button_size')
                    ->label('Dimensione del pulsante')
                    ->live(false, 500)
                    ->options([
                        'sm' => 'Piccolo',
                        'md' => 'Medio',
                        'lg' => 'Grande',
                        'xl' => 'Extra grande',
                        '2xl' => '2x grande',
                    ])
                    ->default('md')
                    ->required(),
                Radio::make('class')
                    ->label('Stile del pulsante')
                    ->columnSpanFull()
                    ->options(function (Get $get) {
                        $buttonText = $get('button_text') ?: 'Pulsante';

                        return self::getButtonStyles($buttonText, $get('button_size'));
                    })
                    ->inline()
                    ->inlineLabel(false),
            ])
            ->collapsed()
            ->cloneable()
            // ->grid(2)
            ->addActionLabel('Aggiungi pulsante')
            ->itemLabel(fn (array $state): ?string => $state['button_text'] ?? null);
    }

    /**
     * Definizione degli stili disponibili per i pulsanti
     * Puoi aggiungere o modificare gli stili qui, dovrai anche modificarli nella vista blade
     * resources\views\components\mason\buttons.blade.php
     */
    private static function getButtonStylesDefinitions(): array
    {
        $commonClasses = 'leading-none rounded-md';
        $options = [
            [
                'class' => 'border-2 border-black bg-transparent text-black hover:bg-quaternary',
                'key' => 'border',
                'label' => 'Bordo',
            ],
            [
                'class' => 'bg-primary text-white hover:bg-primary-700',
                'key' => 'primary',
                'label' => 'Primario',
            ],
            [
                'class' => 'bg-secondary text-white hover:bg-secondary/80',
                'key' => 'secondary',
                'label' => 'Secondario',
            ],
            [
                'class' => 'bg-tertiary text-black hover:bg-tertiary/80',
                'key' => 'tertiary',
                'label' => 'Terziario',
            ],
            [
                'class' => 'bg-accent text-white hover:bg-accent-700',
                'key' => 'accent',
                'label' => 'Accento',
            ],
        ];

        return [
            'commonClasses' => $commonClasses,
            'options' => $options,
        ];
    }

    /**
     * Genera le opzioni di stile per il componente Radio
     */
    private static function getButtonStyles(string $buttonText, string $size = 'md'): array
    {
        $options = [];

        $buttonStyles = self::getButtonStylesDefinitions();
        $commonClasses = $buttonStyles['commonClasses'];

        // Aggiungi la dimensione del pulsante alle classi comuni
        switch ($size) {
            case 'sm':
                $commonClasses .= ' text-sm p-2';
                break;
            case 'lg':
                $commonClasses .= ' text-lg p-2.5';
                break;
            case 'xl':
                $commonClasses .= ' text-xl p-3';
                break;
            case '2xl':
                $commonClasses .= ' text-2xl p-4';
                break;
            default:
                $commonClasses .= ' text-base p-2';
        }

        foreach ($buttonStyles['options'] as $style) {
            $options[$style['key']] = new HtmlString(
                '<div class="flex flex-col items-center gap-2 cursor-pointer">'.
                    '<div class="'.$style['class'].' '.$commonClasses.'">'.
                    $buttonText.
                    '</div>'.
                    $style['label'].
                    '</div>'
            );
        }

        return $options;
    }
}
