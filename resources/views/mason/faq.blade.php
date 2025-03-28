@php
    $props = array_merge(
        [
            'faqs' => [],
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
                    @foreach ($faqs as $index => $faq)
                        <div class="rounded-lg border bg-white">
                            <button
                                @click="openIndex = openIndex === {{ $index }} ? null : {{ $index }}"
                                class="flex w-full items-center gap-4 p-4 text-left"
                            >
                                <span class="font-medium flex-1">{{ $faq['question'] }}</span>
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
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
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
