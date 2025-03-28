@php
    $props = array_merge(
        [
            'showcase' => [],
            'cars' => [],
            'slug' => '',
            'perPage' => 3,
            'pagination' => false,
            'arrows' => true,
            'rewind' => true,
            'carouselGap' => '0.8rem',
        ],
        \App\Mason\Macro\Theme::getProps(),
        \App\Mason\Macro\SectionHeader::getProps(),
    );

    // Converte tutti gli array in oggetti in modo ricorsivo
    $showcase = json_decode(json_encode($showcase));
@endphp

@props($props)

<x-mason.section :theme="$theme">
    <div class="mx-4 xl:mx-0">
        <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />

        @if (request()->path() != 'livewire/update')
            <livewire:dealer-showcase
                :perPage="$perPage"
                :carouselGap="$carouselGap"
                :pagination="$pagination"
                :arrows="$arrows"
                :rewind="$rewind"
                :slug="'showcase-' . Str::random(8)"
                :showcase="$showcase"
            />
        @else
            <div class="relative">
                <button
                    class="absolute -left-8 top-1/2 z-10 -translate-y-1/2 rounded-full border border-gray-200 bg-white p-2 shadow-lg"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @for ($i = 0; $i < 3; $i++)
                        <div
                            class="flex flex-col items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-4"
                        >
                            <div class="aspect-video w-full rounded-lg bg-gray-200 p-8 text-gray-50">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-full rotate-6"
                                    fill="currentColor"
                                    viewBox="0 0 256 256"
                                >
                                    <path
                                        d="M240,104H229.2L201.42,41.5A16,16,0,0,0,186.8,32H69.2a16,16,0,0,0-14.62,9.5L26.8,104H16a8,8,0,0,0,0,16h8v80a16,16,0,0,0,16,16H64a16,16,0,0,0,16-16v-8h96v8a16,16,0,0,0,16,16h24a16,16,0,0,0,16-16V120h8a8,8,0,0,0,0-16ZM80,152H56a8,8,0,0,1,0-16H80a8,8,0,0,1,0,16Zm120,0H176a8,8,0,0,1,0-16h24a8,8,0,0,1,0,16ZM44.31,104,69.2,48H186.8l24.89,56Z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="mt-4 w-full">
                                <div class="h-4 w-3/4 rounded bg-gray-200"></div>
                                <div class="mt-2 h-4 w-1/2 rounded bg-gray-200"></div>
                            </div>
                        </div>
                    @endfor
                </div>
                <button
                    class="absolute -right-8 top-1/2 z-10 -translate-y-1/2 rounded-full border border-gray-200 bg-white p-2 shadow-lg"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        @endif

        @if ($buttons)
            <x-mason.buttons :buttons="$buttons" />
        @endif
    </div>
</x-mason.section>
