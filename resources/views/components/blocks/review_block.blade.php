@props([
    'title' => '',
    'title_align' => 'text-left',
    'tagline' => '',
    'tagline_align' => 'text-left',
    'content' => '',

    'reviews' => [],
    'perPage' => 1,
    'pagination' => 'true',
    'arrows' => 'true',
    'rewind' => 'false',
    'carouselGap' => '0.5rem',

    'buttons' => [],

    // Common
    'textColor' => '',
    'bgColor' => '',
    'blockMaxWidth' => 'max-w-7xl',
    'blockMaxWidthSm' => '',
    'blockMaxWidthMd' => '',
    'blockMaxWidthLg' => '',
    'blockMaxWidthXl' => '',
    'blockMaxWidth2Xl' => '',
    'textColumns' => '',
    'textColumnsSm' => '',
    'textColumnsMd' => '',
    'textColumnsLg' => '',
    'textColumnsXl' => '',
    'textColumns2Xl' => '',
    'blockVerticalPadding' => 'py-12',
    'blockVerticalPaddingSm' => '',
    'blockVerticalPaddingMd' => '',
    'blockVerticalPaddingLg' => 'lg:py-24',
    'blockVerticalPaddingXl' => '',
    'blockVerticalPadding2Xl' => '',
])

@php
    $blockPaddingY = concatStrings($blockVerticalPadding, $blockVerticalPaddingSm, $blockVerticalPaddingMd, $blockVerticalPaddingLg, $blockVerticalPaddingXl, $blockVerticalPadding2Xl);

    $block_css_classes = concatStrings('prose lg:prose-xl prose-p:mt-0 px-4 lg:px-0 max-w-none', $textColor ? 'prose-strong:text-inherit' : null, $bgColor ? null : 'dark:prose-invert', $blockPaddingY);

    $css_block_classes = concatStrings(
        // Max Width
        $blockMaxWidth,
        $blockMaxWidthSm,
        $blockMaxWidthMd,
        $blockMaxWidthLg,
        $blockMaxWidthXl,
        $blockMaxWidth2Xl,

        'mx-auto',
    );

    $css_classes = concatStrings(
        // Max Width
        $blockMaxWidth,
        $blockMaxWidthSm,
        $blockMaxWidthMd,
        $blockMaxWidthLg,
        $blockMaxWidthXl,
        $blockMaxWidth2Xl,

        // Text Columns
        $textColumns,
        $textColumnsSm,
        $textColumnsMd,
        $textColumnsLg,
        $textColumnsXl,
        $textColumns2Xl,

        'mx-auto gap-8',
    );

    $block_style = concatStrings($bgColor ? "background-color: $bgColor;" : null, $textColor ? "color: $textColor !important;" : null);

    $forceTextColor = $textColor ? "color: $textColor !important;" : null;

    $titleAndTagline = $title || $tagline;
@endphp

<div class="{{ $block_css_classes }}" style="{{ $block_style }}">

    @if ($title)
        <div class="{{ $css_block_classes }} {{ $title || $tagline ? 'mb-6' : 'mb-12' }}">
            <h2 class="{{ $title_align }} text-balance" style="{{ $forceTextColor }}">{{ $title }}</h2>
        </div>
    @endif

    @if ($tagline)
        <div class="{{ $css_block_classes }} mb-12">
            <p class="{{ $tagline_align }} text-balance" style="{{ $forceTextColor }}">{{ $tagline }}</p>
        </div>
    @endif

    <div class="not-prose mx-auto max-w-3xl">

        <div x-data="{
            init() {
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
        }">
            <section x-ref="splide" class="splide px-14" aria-label="Video">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach ($reviews as $review)
                            <div class="splide__slide pb-8">
                                <div class="not-prose flex gap-6">
                                    <div class="text-primary-400 text-center text-[8rem] font-bold leading-none">&ldquo;</div>
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
    @if ($buttons)
        <div class="{{ $css_block_classes }} mt-12">
            <x-blocks.macro.buttons :buttons="$buttons" />
        </div>
    @endif
</div>
