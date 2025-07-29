@php
    use Carbon\Carbon;
@endphp

@php
    $image = $page->getFirstMedia('featured_images');
@endphp

<x-layouts.main>
    <x-slot:seo>
        @php
            // Se non c'è un og_image, usa l'immagine principale
            $og_image = $page->getFirstMedia('og_image');

            $title = $page->meta['seo']['tag_title'] ?? $page->title;
            $description = $page->meta['seo']['meta_description'] ?? mason_excerpt($page->content, 155);
        @endphp
        <x-seo>
            <x-slot:title>
                {{ $title }}
            </x-slot>
            <x-slot:description>
                {{ $description }}
            </x-slot>
            <x-slot:image>
                {{-- Se non c'è un og_image, usa l'immagine principale --}}
                {{ $og_image ? $og_image->getUrl() : ($image ? $image->getUrl() : null) }}
            </x-slot>

            <x-slot:og_title>
                {{ $page->meta['og']['title'] ?? $title }}
            </x-slot>
            <x-slot:og_description>
                {{ $page->meta['og']['description'] ?? $description }}
            </x-slot>

            <x-slot:url>{{ request()->url() }}</x-slot>
            <x-slot:type>article</x-slot>

            <x-slot:published_time>{{ Carbon::parse($page->created_at) }}</x-slot>
            <x-slot:modified_time>{{ $page->updated_at }}</x-slot>
        </x-seo>
    </x-slot>
    <div class="post-content" data-aos="fade-up">
        <!-- ======= Single Post Content ======= -->
        <div class="prose max-w-none">
            <div class="mx-auto my-24 max-w-5xl text-balance text-center">
                <div class="text-muted text-sm">
                    <span>{{ Carbon::parse($page->updated_at)->format('D, d M Y') }}</span>
                </div>
                <h1 class="mb-5 text-center leading-normal">{{ $page->title }}</h1>
            </div>
            @if ($image && $page->extras['show_featured_image'])
                <div class="main-column aspect-[16/6] bg-cover bg-center bg-no-repeat"
                    style="background-image: url('{{ $image->getUrl() }}')"></div>
            @endif

            <x-page :content="$page->content" :data="$page" />
        </div>
        <!-- End Single Post Content -->

        <hr />
    </div>
</x-layouts.main>
