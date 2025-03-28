@props([
    'background_color' => 'white',
    'image_position' => null,
    'image_alignment' => null,
    'image_rounded' => false,
    'image_shadow' => false,
    'text' => null,
    'image' => null,
])

<section @class([
    'font-body branded',
    match ($background_color) {
        'primary' => 'bg-primary-500 text-white',
        'secondary' => 'bg-secondary-500 text-white',
        'tertiary' => 'bg-tertiary-500 text-white',
        'accent' => 'bg-accent-500 text-gray-900',
        'gray' => 'bg-gray-100 text-gray-900',
        'white' => 'bg-white text-gray-900',
        default => $background_color,
    },
])>

    <div class="mx-auto w-full max-w-5xl px-6 py-8 2xl:py-12">
        <div @class([
            'grid gap-6 2xl:grid-cols-3',
            'items-center' => $image_alignment === 'middle',
            'items-end' => $image_alignment === 'bottom',
            'items-start' => $image_alignment === 'top',
        ])>
            @if (filled($image))
                <div @class([
                    'not-prose',
                    'order-0' => $image_position === 'start',
                    'order-1' => $image_position === 'end',
                    'items-end' => $image_alignment === 'bottom',
                    'items-start' => $image_alignment === 'top',
                ])>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($image) }}" alt="" @class([
                        'rounded-lg' => $image_rounded,
                        'shadow-md' => $image_shadow,
                    ]) />
                </div>
            @endif

            <div @class([
                '2xl:col-span-2' => filled($image),
                '2xl:col-span-3' => !filled($image),
            ])>
                @if ($text)
                    <div @class([
                        'prose lg:prose-xl prose-headings:font-display max-w-none',
                        match ($background_color) {
                            'primary' => 'prose-invert',
                            'secondary' => 'prose-invert',
                            'tertiary' => 'prose-invert',
                            'accent' => 'bg-accent-500 text-gray-900',
                            'gray' => 'bg-gray-100 text-gray-900',
                            'white' => 'bg-white text-gray-900',
                            default => $background_color,
                        },
                    ])>
                        {!! $text !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
