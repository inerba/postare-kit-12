@php
    $props = array_merge(
        [
            'url' => request()->fullUrl(),
            'title' => '',
            'buttonClass' => 'text-black',
            'svgClass' => 'h-8 w-8',
            'section_title' => '',
        ],
        \App\Mason\Macro\Theme::getProps(),
        \App\Mason\Macro\SectionHeader::getProps(),
    );

    $buttonClass = match ($theme['background_color'] ?? null) {
        'primary' => 'text-white',
        'secondary' => 'text-white',
        'gray' => 'bg-gray-100 text-gray-900',
        'white' => 'bg-white text-gray-900',
        default => 'text-gray-900',
    };
@endphp

@props($props)

<x-mason.section :theme="$theme">
    <div class="mx-4 xl:mx-0">
        <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />
        <div class="flex flex-row justify-around">
            {{-- Facebook --}}
            <a
                onclick="openShareWindow(this.href); return false;"
                href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}"
                target="_blank"
                class="{{ $buttonClass }} transition-all hover:text-[#3b5998]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="{{ $svgClass }}"
                    fill="currentColor"
                    height="16"
                    width="16"
                    viewBox="0 0 512 512"
                >
                    <path
                        d="M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5V334.2H141.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H287V510.1C413.8 494.8 512 386.9 512 256h0z"
                    />
                </svg>
            </a>
            {{-- Twitter --}}
            <a
                onclick="openShareWindow(this.href); return false;"
                href="https://twitter.com/intent/tweet?text={{ $title }}%20-%20{{ $url }}"
                target="_blank"
                class="{{ $buttonClass }} hover:text-[#1da1f2]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="{{ $svgClass }}"
                    fill="currentColor"
                    height="16"
                    width="16"
                    viewBox="0 0 512 512"
                >
                    <path
                        d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"
                    />
                </svg>
            </a>
            {{-- Linkedin --}}
            <a
                class="{{ $buttonClass }} hover:text-[#0a66c2]"
                onclick="openShareWindow(this.href); return false;"
                href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}&title={{ $title }}"
                target="_blank"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="{{ $svgClass }}"
                    fill="currentColor"
                    height="16"
                    width="14"
                    viewBox="0 0 448 512"
                >
                    <path
                        d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"
                    />
                </svg>
            </a>
            {{-- Whatsapp --}}
            <a
                class="{{ $buttonClass }} hover:text-[#25d366]"
                onclick="openShareWindow(this.href); return false;"
                href="https://api.whatsapp.com/send/?text={{ $url }}"
                target="_blank"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="{{ $svgClass }}"
                    fill="currentColor"
                    height="16"
                    width="14"
                    viewBox="0 0 448 512"
                >
                    <path
                        d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"
                    />
                </svg>
            </a>
            {{-- Telegram --}}
            <a
                class="{{ $buttonClass }} hover:text-[#0088cc]"
                onclick="openShareWindow(this.href); return false;"
                href="https://telegram.me/share/url?url={{ $url }}&text={{ $title }}"
                target="_blank"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="{{ $svgClass }}"
                    fill="currentColor"
                    height="16"
                    width="15.5"
                    viewBox="0 0 496 512"
                >
                    <path
                        d="M248 8C111 8 0 119 0 256S111 504 248 504 496 393 496 256 385 8 248 8zM363 176.7c-3.7 39.2-19.9 134.4-28.1 178.3-3.5 18.6-10.3 24.8-16.9 25.4-14.4 1.3-25.3-9.5-39.3-18.7-21.8-14.3-34.2-23.2-55.3-37.2-24.5-16.1-8.6-25 5.3-39.5 3.7-3.8 67.1-61.5 68.3-66.7 .2-.7 .3-3.1-1.2-4.4s-3.6-.8-5.1-.5q-3.3 .7-104.6 69.1-14.8 10.2-26.9 9.9c-8.9-.2-25.9-5-38.6-9.1-15.5-5-27.9-7.7-26.8-16.3q.8-6.7 18.5-13.7 108.4-47.2 144.6-62.3c68.9-28.6 83.2-33.6 92.5-33.8 2.1 0 6.6 .5 9.6 2.9a10.5 10.5 0 0 1 3.5 6.7A43.8 43.8 0 0 1 363 176.7z"
                    />
                </svg>
            </a>

            <script>
                function openShareWindow(url) {
                    window.open(url, 'shareWindow', 'width=600,height=400');
                }
            </script>
        </div>
    </div>
</x-mason.section>
