@php
    use Carbon\Carbon;
@endphp

@extends('portal.layouts.app')

@php
    $image = $post->getFirstMedia('featured_image');
@endphp

@section('title', $post->meta['tag_title'] ?? $post->title)
@section('description', $post->meta['meta_description'] ?? null)

@section('content')
    <div class="post-content" data-aos="fade-up">
        <!-- ======= Single Post Content ======= -->
        <div class="single-post">
            <div class="mx-auto my-24 max-w-5xl text-balance text-center">
                <div class="post-meta">
                    <span class="date font-semibold uppercase tracking-tighter text-gray-500">
                        {{ $post->category->name }}
                    </span>
                    <span class="mx-1">&bullet;</span>
                    <span>{{ Carbon::parse($post->published_at)->format('D, d M Y') }}</span>
                </div>
                <h1 class="mb-5 text-center leading-normal">{{ $post->title }}</h1>
            </div>
            @if ($image && $post->extras['show_featured_image'])
                <div
                    class="main-column aspect-[16/6] bg-cover bg-center bg-no-repeat"
                    style="background-image: url('{{ $image->getUrl() }}')"
                ></div>
            @endif

            <x-mason :post="$post" />
        </div>
        <!-- End Single Post Content -->

        <hr />
    </div>
@endsection

@section('styles')
    <style>
        img {
            display: block;
            max-width: 100%;
            width: auto;
            height: auto;
        }
    </style>
@endsection
