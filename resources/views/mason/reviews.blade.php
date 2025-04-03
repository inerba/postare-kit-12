@php
    $props = array_merge(
        [
            'reviews' => [],
            'perPage' => 1,
            'pagination' => 'true',
            'arrows' => 'true',
            'rewind' => 'false',
            'carouselGap' => '0.5rem',
        ],
        \App\Mason\Macro\Theme::getProps(),
        \App\Mason\Macro\SectionHeader::getProps(),
    );
@endphp

@props($props)

<x-mason.section :theme="$theme">
    <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />
    {{--  --}}
    @if (request()->path() != 'livewire/update')
        <div class="not-prose mx-auto max-w-3xl">
            <div
                x-data="{
                    splideLoaded: false,
                    init() {
                        if (window.Splide) {
                            this.initSplide()
                        } else {
                            document.addEventListener('splide-loaded', () => {
                                this.initSplide()
                            })
                        }
                    },
                    initSplide() {
                        new Splide(this.$refs.splide, {
                            perPage: {{ $perPage ?? 1 }},
                            pagination: true,
                            arrows: true,
                            rewind: true,
                            gap: '{{ $carouselGap ?? '0.5rem' }}',
                            autoplay: true,
                            autoplaySpeed: 4000,
                            pauseOnHover: true,
                            breakpoints: {
                                640: {
                                    perPage: 1,
                                },
                            },
                        }).mount()
                    },
                }"
            >
                <section x-ref="splide" class="splide px-14" aria-label="Video">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($reviews as $review)
                                <div class="splide__slide pb-8">
                                    <div class="not-prose flex gap-6">
                                        <div class="text-primary-400 text-center text-[8rem] font-bold leading-none">
                                            &ldquo;
                                        </div>
                                        <div class="flex-1 pt-8">
                                            <p class="relative italic leading-tight">
                                                {{ $review['content'] }}
                                            </p>
                                            <div class="my-4 h-px w-24 bg-black/50"></div>
                                            <div class="font-bold">
                                                {{ $review['author'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </section>
            </div>
        </div>

        @push('scripts')
            @vite(['resources/js/splide.js'])
        @endpush
    @else
        <div>
            <div class="splide__slide pb-8">
                <div class="not-prose flex gap-6">
                    <div class="text-primary-400 text-center text-[8rem] font-bold leading-none">&ldquo;</div>
                    <div class="flex-1 pt-8">
                        <p class="relative italic leading-tight">
                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Itaque facilis aut accusantium
                            sapiente aliquam. Quam vitae, ab rem omnis eligendi, nobis quisquam corporis non facere
                            dolorum aliquid labore repellat autem.
                        </p>
                        <div class="my-4 h-px w-24 bg-black/50"></div>
                        <div class="font-bold">Lorem Ipsum</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{--  --}}
    @if ($buttons)
        <x-mason.buttons :buttons="$buttons" />
    @endif
</x-mason.section>
