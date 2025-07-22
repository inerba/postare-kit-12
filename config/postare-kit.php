<?php

return [

    'panel_path' => env('PANEL_PATH', 'auth'),

    'timezone' => 'Europe/Rome', // Default timezone for date and datetime pickers

    // Cache
    'cache' => [
        // Default cache duration in minutes
        'default_duration' => 60 * 24 * 7, // 7 days
    ],

    'middleware' => [
        'web',
    ],

    'media' => [
        'disk' => 'public',
        'format' => 'jpg', // jpg, png, webp
        'quality' => 80, // 0-100

        // Usa Spatie Laravel-medialibrary per le conversioni delle immagini,
        // per rigenerarle in massa usa il comando php artisan media-library:regenerate
        // commenta le conversioni che non ti servono
        'conversions' => [ // max width in pixels
            //  'xl' => 1920,
            'lg' => 1280,
            'md' => 400,
            'sm' => 200,
            //  'xs' => 100,
        ],
    ],

    // Defaults for Seo and Social
    'seo' => [
        'author' => null,
    ],

    'og' => [
        'type' => 'article',
        'locale' => 'it_IT',
        'site_name' => null,
        'twitter_username' => null, // @username
        'fb_app_id' => 169565392540441, // Facebook App ID
    ],

    // if not set, AI will be disabled
    'openai_api_key' => env('OPENAI_API_KEY'),

    // Default OpenAI Model (refer to https://platform.openai.com/docs/models)
    'default_openai_model' => 'gpt-4o-mini',

    // SEO Prompt
    'seo_prompt' => 'Dato il seguente post del blog in formato JSON, genera un title e una meta description ottimizzati per SEO. Il title non deve superare i 60 caratteri. La meta description deve essere compatta e persuasiva, con una lunghezza compresa tra 150 e 160 caratteri.',
    'seo_tag_title' => 'Genera un title ottimizzato per SEO, non superiore a 60 caratteri.',
    'seo_meta_description' => 'Genera una meta description ottimizzata per SEO, compatta e persuasiva, con una lunghezza compresa tra 150 e 160 caratteri.',
];
