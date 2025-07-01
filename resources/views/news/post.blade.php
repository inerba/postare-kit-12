@php
    $image = $post->getFirstMedia('featured_image'); // Copertina
    $og_image = $post->getFirstMedia('og_image'); // Immagine per Open Graph
@endphp

<x-layouts.main>
    <x-slot:seo>
        <x-seo
            :title="$post->meta['tag_title'] ?? $post->title"
            :description="$post->meta['meta_description'] ?? null"
            :og_title="$post->meta['og']['title'] ?? ($post->meta['tag_title'] ?? $post->title)"
            :og_description="$post->meta['og']['description'] ?? ($post->meta['meta_description'] ?? null)"
            :image="$og_image ? $og_image->getUrl() : ($image ? $image->getUrl() : null)"
        />
    </x-slot>
    <article>
        {{-- Header --}}
        <x-posts.header :category="$post->category">
            <x-slot:title>
                {{ $post->title }}
            </x-slot>
            <x-slot:date>{{ $post->created_at->translatedFormat('d M y') }}</x-slot>
            <x-slot:author>{{ $post->author->name }}</x-slot>
        </x-posts.header>

        {{-- Copertina --}}
        @if ($image && $post->extras['show_featured_image'])
            <x-posts.featured-image-cover :image_url="$image->getUrl()" />
        @endif

        {{-- Contenuto --}}
        <x-post :$post />
    </article>
</x-layouts.main>
