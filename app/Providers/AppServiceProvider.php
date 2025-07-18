<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    private function configureModels(): void
    {
        Model::shouldBeStrict(! app()->isProduction());
    }

    private function configureFilamentFormComponents(): void
    {
        // Aggiungo un'azione di pulizia HTML TiptapEditor
        \FilamentTiptapEditor\TiptapEditor::configureUsing(function (\FilamentTiptapEditor\TiptapEditor $component) {
            $component
                ->extraInputAttributes(['style' => 'min-height: 12rem;'])
                ->hintAction(\App\Filament\Actions\Forms\HtmlCleanAction::make());
        });

        // Imposto la timezone di default
        \Filament\Forms\Components\DatePicker::configureUsing(fn (\Filament\Forms\Components\DatePicker $component) => $component->timezone(config('postare-kit.timezone')));
        \Filament\Forms\Components\DateTimePicker::configureUsing(fn (\Filament\Forms\Components\DateTimePicker $component) => $component->timezone(config('postare-kit.timezone')));
    }

    private function configureFilamentTableComponents(): void
    {
        // Imposto la timezone di default
        \Filament\Tables\Columns\TextColumn::configureUsing(fn (\Filament\Tables\Columns\TextColumn $column) => $column->timezone(config('postare-kit.timezone')));
    }

    public function boot(): void
    {
        $this->configureCommands();
        $this->configureModels();
        $this->configureFilamentFormComponents();
        $this->configureFilamentTableComponents();

        Filament::registerNavigationItems([
            NavigationItem::make('Frontend')
                ->url('/', shouldOpenInNewTab: true)
                ->icon('heroicon-o-arrow-top-right-on-square')
                // ->group('Link esterni')
                ->sort(1),
        ]);
    }
}
