@props([
    'title' => null,
    'subtitle' => null,
    'align' => 'left',
])
@if ($title)
    <div @class([
        'mb-6 text-balance prose-h2:mt-0',
        match ($align) {
            'right' => 'text-right',
            'center' => 'text-center',
            default => null,
        },
        $subtitle ? 'prose-h2:mb-4' : null,
    ])>
        <h2>{!! nl2br($title) !!}</h2>
        @if ($subtitle)
            <p class="mt-2 text-2xl">{{ $subtitle }}</p>
        @endif
    </div>
@endif
