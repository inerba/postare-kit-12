<?php

namespace App\Filament\Actions\Forms;

use App\Helpers\CleanHtml;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;

class HtmlCleanAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'htmlClean';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('pages.resources.post.form.clean_html'))
            ->icon('heroicon-o-document-check')
            ->action(function (Get $get, Set $set, $component) {

                $field_name = $component->getName();

                $content = $get($field_name);

                if (empty($content)) {
                    return;
                }

                $old_content = tiptap_converter()->asHTML($content);

                $clean_content = CleanHtml::clean($old_content);

                $set($field_name, $clean_content);

                Notification::make()
                    ->title(__('pages.resources.post.success.html_cleaned'))
                    ->success()
                    ->send();
            });
    }
}
