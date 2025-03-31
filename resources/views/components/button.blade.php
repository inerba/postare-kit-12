@props([
    'text' => '',
    'target' => '',
    'href' => '',
    'class' => 'bg-green-700 text-white hover:bg-green-800',
])
<a
    href="{{ $href }}"
    target="{{ $target }}"
    @class([
        $class,
        'not-prose flex items-center justify-between rounded px-4 py-2 font-bold uppercase transition-all',
    ])
>
    {{ $text }}
</a>
