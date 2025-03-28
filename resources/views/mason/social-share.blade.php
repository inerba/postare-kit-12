@php
    $props = array_merge(
        [
            'url' => request()->fullUrl(),
            'title' => '',
            'buttonClass' => 'text-black',
            'svgClass' => 'h-8 w-8',
            'section_title' => '',
        ],
        \App\Mason\Macro\Theme::getProps(),
        \App\Mason\Macro\SectionHeader::getProps(),
    );

    $buttonClass = match ($theme['background_color'] ?? null) {
        'primary' => 'text-white',
        'secondary' => 'text-white',
        'gray' => 'bg-gray-100 text-gray-900',
        'white' => 'bg-white text-gray-900',
        default => 'text-gray-900',
    };
@endphp

@props($props)

<x-mason.section :theme="$theme">
    <div class="mx-4 xl:mx-0">
        <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />
        <x-social-share :$title :$url :$buttonClass :$svgClass />
    </div>
</x-mason.section>
