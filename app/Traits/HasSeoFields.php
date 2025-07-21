<?php

namespace App\Traits;

use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
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
