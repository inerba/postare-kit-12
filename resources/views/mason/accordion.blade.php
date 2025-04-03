@php
    $props = array_merge(
        [
            'accordion' => [],
        ],
        \App\Mason\Macro\Theme::getProps(),
        \App\Mason\Macro\SectionHeader::getProps(),
    );
@endphp

@props($props)

<x-mason.section :theme="$theme">
    <div class="mx-4 xl:mx-0">
        <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />

        <div class="not-prose mx-auto max-w-3xl" x-data="{ openIndex: null }">
            <div class="space-y-4">
                @foreach ($accordion as $index => $faq)
                    <div class="rounded-lg border bg-white">
                        <button
                            @click="openIndex = openIndex === {{ $index }} ? null : {{ $index }}"
                            class="flex w-full items-center gap-4 p-4 text-left"
                        >
                            <span class="flex-1 font-medium">{{ $faq['question'] }}</span>
                            <svg
                                class="size-8 shrink-0 transform transition-transform duration-200"
                                :class="{ 'rotate-180': openIndex === {{ $index }} }"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                        <div
                            x-show="openIndex === {{ $index }}"
                            x-transition:enter="transition duration-200 ease-out"
                            x-transition:enter-start="-translate-y-2 transform opacity-0"
                            x-transition:enter-end="translate-y-0 transform opacity-100"
                            x-transition:leave="transition duration-150 ease-in"
                            x-transition:leave-start="translate-y-0 transform opacity-100"
                            x-transition:leave-end="-translate-y-2 transform opacity-0"
                            class="border-t p-4"
                        >
                            <div class="prose max-w-none">
                                {!! $faq['answer'] !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($buttons)
            <x-mason.buttons :buttons="$buttons" />
        @endif
    </div>
</x-mason.section>
