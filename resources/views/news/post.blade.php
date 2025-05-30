@dump($post->toArray())
@php
    $image = $post->getFirstMedia('featured_image'); // Copertina
    $og_image = $post->getFirstMedia('og_image'); // Immagine per Open Graph
@endphp

@section('title', $post->meta['tag_title'] ?? $post->title)
@section('description', $post->meta['meta_description'] ?? null)
@section('og')
    <x-og>
        <x-slot:title>
            {{ $post->meta['og']['title'] ?? ($post->meta['tag_title'] ?? $post->title) }}
        </x-slot>
        <x-slot:description>
            {{ $post->meta['og']['description'] ?? ($post->meta['meta_description'] ?? null) }}
        </x-slot>
        <x-slot:image>
            {{ $og_image ? $og_image->getUrl() : ($image ? $image->getUrl() : null) }}
        </x-slot>
        <x-slot:url>{{ request()->url() }}</x-slot>
        <x-slot:type>article</x-slot>
    </x-og>
@endsection

<x-layouts.main>
    <article>
        {{-- Header --}}
        <x-posts.header :category="$post->category">
            <x-slot:title>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. {{ $post->title }}
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
