<?php

namespace App\Traits;

use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Postare\ModelAi\ModelAi;

trait HasSeoFields
{
    /**
     * Calcola il numero di caratteri rimanenti per un campo di testo SEO.
     *
     * @param  string  $state  Il testo corrente del campo.
     * @param  int  $maxCharacters  Il numero massimo di caratteri consentiti.
     * @return string Una stringa che indica il conteggio dei caratteri e quelli rimanenti.
     */
    public static function remainingText($state, $maxCharacters = 60)
    {
        $charactersCount = strlen($state);
        $leftCharacters = $maxCharacters - ($charactersCount % $maxCharacters);

        return "$charactersCount / $maxCharacters ($leftCharacters)";
    }

    /**
     * Restituisce i campi SEO per un modello specifico.
     *
     * @param  string  $prefix  Il prefisso per i campi SEO.
     * @param  array<string>|null  $aiSeoFieldsFrom  Un array opzionale che specifica da quali campi generare i metadati SEO tramite AI.
     * @return array<int, Forms\Components\Component> Un array di componenti dei campi SEO.
     */
    protected static function getSeoFields($prefix, ?array $aiSeoFieldsFrom = null): array
    {
        return [
            Forms\Components\TextInput::make($prefix.'.seo.tag_title')
                ->hint(fn ($state): string => self::remainingText($state, 60))
                ->live()
                ->label(__('pages.form.tag_title'))
                ->helperText(__('pages.seo.tag_title_helper'))
                ->columnSpanFull(),
            Forms\Components\TextInput::make($prefix.'.seo.meta_description')
                ->hint(fn ($state): string => self::remainingText($state, 160))
                ->live()
                ->label(__('pages.form.meta_description'))
                ->helperText(__('pages.seo.meta_description_helper'))
                ->columnSpanFull(),

            Section::make('Suggerimenti SEO')
                ->icon('heroicon-o-information-circle')
                ->iconPosition('start')
                ->iconColor('info')
                ->description('Consigli per scrivere un buon title e una buona meta description')
                ->schema([
                    Placeholder::make('seo_tip_1')
                        ->hiddenLabel()
                        ->content(new HtmlString(
                            '<h3 class="text-2xl">Come scrivere un buon title</h3>
  <ul class="list-disc p-6">
    <li><strong>Cos’è?</strong> Il titolo che appare nei risultati di Google (la riga blu cliccabile).</li>
    <li><strong>A cosa serve?</strong> A dire subito di cosa parla la pagina e a far venire voglia di cliccare.</li>
    <li><strong>Scrivilo in modo chiaro:</strong> evita frasi vaghe, vai dritto al punto.</li>
    <li><strong>Usa parole chiave:</strong> inserisci 1 o 2 parole che le persone potrebbero cercare.</li>
    <li><strong>Non esagerare con la lunghezza:</strong> resta sotto i 60 caratteri per non farlo tagliare.</li>
    <li><strong>Ogni pagina ha il suo:</strong> scrivi un titolo diverso per ogni pagina del sito.</li>
  </ul>'
                        )),
                    Placeholder::make('seo_tip_2')
                        ->hiddenLabel()
                        ->content(new HtmlString(
                            '<h3 class="text-2xl">Come scrivere una buona meta description</h3>
                            <ul class="list-disc p-6">
    <li><strong>Cos’è?</strong> Una breve frase che riassume la pagina e appare nei risultati di Google.</li>
    <li><strong>A cosa serve?</strong> A far capire di cosa parla la pagina e invogliare le persone a cliccare.</li>
    <li><strong>Scrivila tu!</strong> Non lasciare che Google la scelga a caso: è meglio se la scrivi tu.</li>
    <li><strong>Fatti notare:</strong> inizia con parole che attirano l’attenzione.</li>
    <li><strong>Parla al tuo pubblico:</strong> usa parole semplici e adatte a chi vuoi raggiungere.</li>
    <li><strong>Invita a cliccare:</strong> chiudi con una frase che spinga all’azione (es. “Scopri di più”).</li>
    <li><strong>Non troppo lunga:</strong> massimo 150 caratteri, altrimenti verrà tagliata.</li>
    <li><strong>Ogni pagina la sua:</strong> usa una frase diversa per ogni pagina.</li>
  </ul>'
                        )),
                ])
                ->collapsible()
                ->collapsed(),

            // Actions::make([
            //     Action::make('generate_seo')
            //         ->hidden(fn() => empty(config('postare-kit.openai_api_key')) || empty($aiSeoFieldsFrom))
            //         ->label(__('pages.form.generate_seo'))
            //         ->badge()
            //         ->color('success')
            //         ->icon('heroicon-m-sparkles')

            //         ->fillForm(function (Action $action, Get $get) use ($aiSeoFieldsFrom): array {

            //             $title = $get($aiSeoFieldsFrom[0]);
            //             $description = $get($aiSeoFieldsFrom[1]);
            //             $content = str(tiptap_converter()->asText($description))->limit(1000);

            //             $knowledge = collect([
            //                 'blog_post' => $title . "\n " . $content,
            //             ])->toJson();

            //             // Se title o content sono nulli, non genero nulla
            //             if (empty($title) || empty($description)) {

            //                 Notification::make()
            //                     ->title('Errore')
            //                     ->body('Devi prima inserire un titolo e un contenuto per generare il SEO')
            //                     ->warning()
            //                     ->send();

            //                 $action->cancel();
            //             }

            //             $data = ModelAi::chat()
            //                 ->prompt(config('postare-kit.seo_prompt') . $knowledge)
            //                 ->function([
            //                     'name' => 'get_meta_tags',
            //                     'description' => 'Ricava i meta tag sulla base dei dati forniti',
            //                     'parameters' => [
            //                         'type' => 'object',
            //                         'properties' => [
            //                             'title' => [
            //                                 'type' => 'string',
            //                                 'description' => config('postare-kit.seo_tag_title'),
            //                             ],
            //                             'description' => [
            //                                 'type' => 'string',
            //                                 'description' => config('postare-kit.seo_meta_description'),
            //                             ],
            //                         ],
            //                         'required' => ['title', 'description'],
            //                     ],
            //                 ])
            //                 ->send();

            //             return [
            //                 'new_description' => $data->description,
            //                 'new_title' => $data->title,
            //             ];
            //         })
            //         ->form([
            //             Forms\Components\Textarea::make('new_title')
            //                 ->label('Nuovo tag tite')
            //                 ->columnSpanFull(),
            //             Forms\Components\Textarea::make('new_description')
            //                 ->label('Nuova meta_description')
            //                 ->columnSpanFull(),
            //         ])
            //         ->action(function (array $data, Forms\Set $set) use ($prefix): void {
            //             $set($prefix . '.seo.tag_title', $data['new_title']);
            //             $set($prefix . '.seo.meta_description', $data['new_description']);
            //         })
            //         ->modalSubmitActionLabel('Salva questi valori')
            //         ->modalWidth('md')
            //         ->slideOver(),
            // ]),

        ];
    }
}
