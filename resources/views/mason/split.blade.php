@use('Illuminate\Support\Str')

@php
    $props = array_merge(['text' => null], \App\Mason\Macro\Theme::getProps(), \App\Mason\Macro\SectionHeader::getProps());
@endphp

@props($props)

<x-mason.section :theme="$theme">
    <div class="mx-4 grid items-center md:grid-cols-2 xl:mx-0">
        <div @class(['p-24', 'order-last' => Str::contains($layout, '_txt')])>
            <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />
            {!! tiptap_converter()->asHtml($text) !!}
        </div>

        @if (Str::contains($layout, 'img'))
            @if ($img_cover)
                <div
                    class="not-prose h-full w-full bg-cover bg-center"
                    style="background-image: url('/storage/{{ $image }}')"
                ></div>
            @else
                <div class="not-prose">
                    <img src="/storage/{{ $image }}" class="not-prose mx-auto" />
                </div>
            @endif
        @endif

        {{--
            @if ($buttons)
            <x-mason.buttons :buttons="$buttons" />
            @endif
        --}}
    </div>
</x-mason.section>
