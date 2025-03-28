@aware(['post'])

@php
    $props = array_merge(
        [
            'gallery_rand' => '',
            'layout' => 'grid',
            'thumbnail' => 'sm',
            'perPage' => 3,
            'pagination' => true,
            'arrows' => true,
            'rewind' => true,
            'columns' => '',
            'columnsSm' => '',
            'columnsMd' => '',
            'columnsLg' => '',
            'columnsXl' => '',
            'columns2Xl' => '',
        ],
        \App\Mason\Macro\Theme::getProps(),
        \App\Mason\Macro\SectionHeader::getProps(),
    );
@endphp

@props($props)

<x-mason.section :theme="$theme">
    <div class="mx-4 xl:mx-0">
        @if ($header_title || $header_tagline)
            <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />
        @endif

        @if (isset($post))
            @php
                $media_collection = $gallery_rand . '_gallery';
                $gallery = $post->getMedia($media_collection);
            @endphp

            @if ($layout == 'grid')
                <div
                    @class([
                        'not-prose grid gap-4',
                        $columns ? 'grid-cols-' . $columns : null,
                        $columnsSm ? 'sm:grid-cols-' . $columnsSm : null,
                        $columnsMd ? 'md:grid-cols-' . $columnsMd : null,
                        $columnsLg ? 'lg:grid-cols-' . $columnsLg : null,
                        $columnsXl ? 'xl:grid-cols-' . $columnsXl : null,
                        $columns2Xl ? '2xl:grid-cols-' . $columns2Xl : null,
                    ])
                    x-data
                >
                    @foreach ($gallery as $media)
                        <a
                            href="{{ $media->getUrl('lg') }}"
                            class="glightbox transition duration-300 ease-in-out hover:scale-105 hover:shadow-lg hover:brightness-125"
                            data-gallery="{{ $media_collection }}"
                        >
                            <img src="{{ $media->getUrl($thumbnail) }}" alt="{{ $media->name }}" class="rounded-lg" />
                        </a>
                    @endforeach
                </div>
            @elseif ($layout == 'carousel')
                <div
                    x-data="{
                        init() {
                            new Splide(this.$refs.splide, {
                                perPage: {{ $perPage ?? 3 }},
                                pagination: {{ $pagination ? 'true' : 'false' }},
                                arrows: {{ $arrows ? 'true' : 'false' }},
                                rewind: {{ $rewind ? 'true' : 'false' }},
                                // autoHeight: true,
                                gap: '{{ $carouselGap ?? '0.5rem' }}',
                                breakpoints: {
                                    640: {
                                        perPage: 1,
                                    },
                                },
                            }).mount()
                        },
                    }"
                    class="not-prose"
                >
                    <section x-ref="splide" class="splide px-14" aria-label="Gallery">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach ($gallery as $media)
                                    <li class="splide__slide flex flex-col items-center justify-center pb-8">
                                        <a
                                            href="{{ $media->getUrl('lg') }}"
                                            class="glightbox transition duration-300 ease-in-out hover:shadow-lg hover:brightness-125"
                                            data-gallery="{{ $media_collection }}"
                                        >
                                            <img
                                                src="{{ $media->getUrl($thumbnail) }}"
                                                alt="{{ $media->name }}"
                                                class="rounded-lg"
                                            />
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </section>
                </div>
            @endif
        @else
            <div>
                <div @class([
                    'not-prose grid grid-cols-3 gap-4',
                ]) x-data>
                    @for ($i = 0; $i < 6; $i++)
                        <div
                            class="flex aspect-video items-center justify-center rounded-lg border bg-white text-gray-200"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="size-32"
                                fill="currentColor"
                                viewBox="0 0 256 256"
                            >
                                <path
                                    d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,16V158.75l-26.07-26.06a16,16,0,0,0-22.63,0l-20,20-44-44a16,16,0,0,0-22.62,0L40,149.37V56ZM40,172l52-52,80,80H40Zm176,28H194.63l-36-36,20-20L216,181.38V200ZM144,100a12,12,0,1,1,12,12A12,12,0,0,1,144,100Z"
                                ></path>
                            </svg>
                        </div>
                    @endfor
                </div>
                <div class="mt-6 rounded-lg bg-white/70 p-4 text-center text-xs text-gray-700">
                    Questo è solo un segnaposto, la vera gallery potrai vederla nella pagina pubblicata.
                </div>
            </div>
        @endif
    </div>
</x-mason.section>
