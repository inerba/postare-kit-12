@php
    $props = array_merge(
        ['code' => null],
        \App\Mason\Macro\Theme::getProps(),
        \App\Mason\Macro\SectionHeader::getProps(),
    );

    $isLivewireRequest = request()->path() === 'livewire/update';
@endphp

@props($props)

<x-mason.section :theme="$theme">
    <div class="mx-4 xl:mx-0">
        <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />

        @if ($isLivewireRequest)
            <div class="-z-100 relative p-12">
                <div class="flex w-full flex-col items-center justify-center gap-2 bg-gray-200 p-8 text-white">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="size-28 animate-pulse"
                        fill="currentColor"
                        viewBox="0 0 256 256"
                    >
                        <path
                            d="M69.12,94.15,28.5,128l40.62,33.85a8,8,0,1,1-10.24,12.29l-48-40a8,8,0,0,1,0-12.29l48-40a8,8,0,0,1,10.24,12.3Zm176,27.7-48-40a8,8,0,1,0-10.24,12.3L227.5,128l-40.62,33.85a8,8,0,1,0,10.24,12.29l48-40a8,8,0,0,0,0-12.29ZM162.73,32.48a8,8,0,0,0-10.25,4.79l-64,176a8,8,0,0,0,4.79,10.26A8.14,8.14,0,0,0,96,224a8,8,0,0,0,7.52-5.27l64-176A8,8,0,0,0,162.73,32.48Z"
                        ></path>
                    </svg>
                    {{--
                        <div class="absolute bottom-2.5 text-center text-xs text-gray-400">
                        Questo è solo un segnaposto, il vero blocco lo vedrai nella pagina pubblicata.
                        </div>
                    --}}
                </div>
            </div>
        @else
            {!! $code !!}
        @endif
        @if ($buttons)
            <x-mason.buttons :buttons="$buttons" />
        @endif
    </div>
</x-mason.section>
