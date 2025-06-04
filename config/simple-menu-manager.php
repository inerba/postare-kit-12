<?php

use App\Filament\Resources\MenuResource\MenuTypeHandlers;

return [
    // Resource configuration
    'navigation_group' => 'Impostazioni',
    'navigation_sort' => 1,
    'model_label' => 'Menu',
    'plural_model_label' => 'Menu',

    // Menu Model
    'model' => App\Models\Menu::class,

    /**
     * Menu Type Handlers
     * Add your custom menu type handlers here.
     * */
    'handlers' => [
        'link' => MenuTypeHandlers\LinkType::class,
        'page' => MenuTypeHandlers\PageType::class,
        'placeholder' => MenuTypeHandlers\PlaceholderType::class,
    ],

    // Livewire component
    'menu_cache' => 1, // Cache time in seconds, each menu has its own cache
];
