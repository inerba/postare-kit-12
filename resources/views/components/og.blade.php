@props(['title' => '', 'description' => '', 'image' => '', 'url' => '', 'type' => '', 'fb_app_id' => '169565392540441'])
<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />
@if ($image)
    <meta property="og:image" content="{{ $image }}" />
@endif
<meta property="og:url" content="{{ $url }}" />
<meta property="og:type" content="{{ $type }}" />
<meta property="fb:app_id" content="{{ $fb_app_id }}" />
