@php
    $props = array_merge(['image' => null], \App\Mason\Macro\Theme::getProps(), \App\Mason\Macro\SectionHeader::getProps());
@endphp

@props($props)

<x-mason.section :theme="$theme">
    @if ($header_title || $header_tagline)
        <div class="mx-4 xl:mx-0">
            <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />
        </div>
    @endif

    <img src="/storage/{{ $image }}" class="not-prose mx-auto" />
</x-mason.section>
