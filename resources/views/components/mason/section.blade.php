@aware(['theme'])

@props([
    'theme',
])

<section
    @class([
        'font-body branded prose lg:prose-xl prose-p:mt-0 max-w-none',
        match ($theme['background_color'] ?? null) {
            'primary' => 'prose-invert bg-red-700 text-white',
            'secondary' => 'prose-invert bg-green-700',
            'gray' => 'bg-gray-100 text-gray-900',
            'white' => 'bg-white text-gray-900',
            default => $theme['background_color'] ?? null,
        },
    ])
>
    <div
        @class([
            'mx-auto',
            $theme['blockMaxWidth'] ?? null,
            $theme['blockMaxWidthSm'] ?? null,
            $theme['blockMaxWidthMd'] ?? null,
            $theme['blockMaxWidthLg'] ?? null,
            $theme['blockMaxWidthXl'] ?? null,
            $theme['blockMaxWidth2Xl'] ?? null,
            $theme['blockVerticalPadding'] ?? null,
            $theme['blockVerticalPaddingSm'] ?? null,
            $theme['blockVerticalPaddingMd'] ?? null,
            $theme['blockVerticalPaddingLg'] ?? null,
            $theme['blockVerticalPaddingXl'] ?? null,
            $theme['blockVerticalPadding2Xl'] ?? null,
            $theme['blockVerticalMargin'] ?? null,
            $theme['blockVerticalMarginSm'] ?? null,
            $theme['blockVerticalMarginMd'] ?? null,
            $theme['blockVerticalMarginLg'] ?? null,
            $theme['blockVerticalMarginXl'] ?? null,
            $theme['blockVerticalMargin2Xl'] ?? null,
        ])
    >
        {{ $slot }}
    </div>
</section>
