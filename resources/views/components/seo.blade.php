@props([
    'title' => '',
    'description' => '',
    'og_title' => '',
    'og_description' => '',
    'image' => '',
    'url' => '',
    'type' => 'article',
    'published_time' => null,
    'modified_time' => null,
    'fb_app_id' => config('ams.facebbook_app_id', '169565392540441'),
])

@php
    $og_title = $og_title ?: $title;
    $og_description = $og_description ?: $description;
    $url = $url ?: url()->current();
@endphp

{{-- Essenziali --}}
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}" />
<link rel="canonical" href="{{ $url }}" />

{{-- <meta name="generator" content="PostareKit" /> --}}

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ config('app.name') }}" />
<meta property="og:locale" content="{{ app()->getLocale() }}" />
<meta property="og:title" content="{{ $og_title }}" />
<meta property="og:description" content="{{ $og_description }}" />
@if ($image)
    {{-- indica ai motori di ricerca (come Google) che possono mostrare anteprime di immagini di grandi dimensioni nei risultati di ricerca. --}}
    <meta name="robots" content="max-image-preview:large" />
    <meta property="og:image" content="{{ $image }}" />
    <meta property="og:image:secure_url" content="{{ $image }}" />
@endif

<meta property="og:url" content="{{ $url }}" />
<meta property="og:type" content="{{ $type }}" />
@if ($published_time && $modified_time)
    <meta property="article:published_time"
        content="{{ $published_time instanceof \Carbon\Carbon ? $published_time->toIso8601String() : \Carbon\Carbon::parse($published_time)->toIso8601String() }}" />
    <meta property="article:modified_time"
        content="{{ $modified_time instanceof \Carbon\Carbon ? $modified_time->toIso8601String() : \Carbon\Carbon::parse($modified_time)->toIso8601String() }}" />
@endif

<meta property="fb:app_id" content="{{ $fb_app_id }}" />

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $og_title }}" />
<meta name="twitter:description" content="{{ $og_description }}" />
@if ($image)
    <meta name="twitter:image" content="{{ $image }}" />
@endif

