@aware(["theme"])

@props([
    "theme",
])

@php
    // Estrazione delle risorse media con null coalescing operator
    $image = $theme["image"] ?? null;
    $isVideo = $theme["is_video"] ?? false;
    $video_mp4 = $theme["video_mp4"] ?? null;
    $video_webm = $theme["video_webm"] ?? null;

    // Poster per il video
    $poster = is_string($image) ? "poster=/storage/{$image}" : null;

    // Controllo del tipo di background
    $isVideoBg = ($theme["use_bg"] ?? false) && $isVideo && $video_mp4;
    $isImageBg = ($theme["use_bg"] ?? false) && ! $isVideo && $image;

    $height = $theme["height"] ?? null;

    // Helper per le classi di background
    // Modifica i colori in app\Mason\Macro\Theme.php
    $backgroundColor = match ($theme["background_color"] ?? null) {
        "white" => "bg-white text-gray-900",
        "gray" => "bg-gray text-gray-900",
        "primary" => "prose-invert bg-primary text-white",
        "secondary" => "prose-invert bg-secondary text-white",
        "tertiary" => "bg-tertiary text-gray-900",
        "quaternary" => "bg-quaternary text-gray-900",
        "accent" => "bg-accent prose-invert text-white",
        default => $theme["background_color"] ?? null,
    };
@endphp

<section
    @class([
        // Classi base di tipografia e layout
        "font-body branded prose lg:prose-xl prose-p:mt-0 relative max-w-none",

        // Colore di sfondo
        $backgroundColor,

        // Condizionali per testo invertito
        "prose-invert" =>
            ($isVideoBg || $isImageBg) && isset($theme["proseInvert"]),

        // Condizionali per altezza e overflow
        $height . " overflow-hidden" =>
            ($isVideoBg || $isImageBg) && ($height ?? false),
    ])
    @if ($isImageBg)
        @style([
            "background-image: url(/storage/" . $image . ");" => $image,
            "background-size: cover;" => $image,
            "background-position: center;" => $image,
            "background-repeat: no-repeat;" => $image,
        ])
    @endif
>
    {{-- Background video (se presente) --}}
    @if ($isVideoBg)
        <video autoplay muted loop playsinline {{ $poster }} class="absolute inset-0 h-full w-full object-cover">
            <source src="/storage/{{ $video_mp4 }}" type="video/mp4" />
            @if ($video_webm)
                <source src="/storage/{{ $video_webm }}" type="video/webm" />
            @endif

            Il tuo browser non supporta il tag video.
        </video>
    @endif

    {{-- Overlay per sfondo --}}
    <div
        @class([
            "absolute inset-0",
            $theme["overlayTransparency"] ?? null => $isVideoBg || $isImageBg,
        ])
    ></div>

    {{-- Contenuto principale --}}
    <div
        @class([
            "relative z-10 mx-auto",

            // Larghezza massima del blocco
            $theme["blockMaxWidth"] ?? null,
            $theme["blockMaxWidthSm"] ?? null,
            $theme["blockMaxWidthMd"] ?? null,
            $theme["blockMaxWidthLg"] ?? null,
            $theme["blockMaxWidthXl"] ?? null,
            $theme["blockMaxWidth2Xl"] ?? null,

            // Padding verticale
            $theme["blockVerticalPadding"] ?? null,
            $theme["blockVerticalPaddingSm"] ?? null,
            $theme["blockVerticalPaddingMd"] ?? null,
            $theme["blockVerticalPaddingLg"] ?? null,
            $theme["blockVerticalPaddingXl"] ?? null,
            $theme["blockVerticalPadding2Xl"] ?? null,

            // Margine verticale
            $theme["blockVerticalMargin"] ?? null,
            $theme["blockVerticalMarginSm"] ?? null,
            $theme["blockVerticalMarginMd"] ?? null,
            $theme["blockVerticalMarginLg"] ?? null,
            $theme["blockVerticalMarginXl"] ?? null,
            $theme["blockVerticalMargin2Xl"] ?? null,
        ])
    >
        {{ $slot }}
    </div>
</section>
