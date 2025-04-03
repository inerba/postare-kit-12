@aware(['dealer'])
@php
    $props = array_merge(
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

@php
    $isLivewireRequest = request()->path() === 'livewire/update';
@endphp

<x-mason.section :theme="$theme">
    <div class="mx-4 xl:mx-0">
        <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />

        @if ($isLivewireRequest)
            <div class="flex flex-col gap-4 bg-white p-6 lg:flex-row">
                <form class="w-full">
                    <div class="mb-4">
                        <label class="mb-2 block font-medium text-gray-700" for="name">Nome</label>
                        <input class="w-full border border-gray-400 p-2" type="text" id="name" />
                    </div>

                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div class="">
                            <label class="mb-2 block font-medium text-gray-700" for="email">Email</label>
                            <input class="w-full border border-gray-400 p-2" type="email" id="email" />
                        </div>
                        <div class="">
                            <label class="mb-2 block font-medium text-gray-700" for="phone">Telefono</label>
                            <input class="w-full border border-gray-400 p-2" type="text" id="phone" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="mb-2 block font-medium text-gray-700" for="message">Messaggio</label>
                        <textarea class="h-32 w-full border border-gray-400 p-2" id="message">
{{ $body ?? '' }}
</textarea
                        >
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" class="form-checkbox" id="gdprConsent" required />
                            <label class="ml-2 text-sm text-gray-700" for="gdprConsent">
                                Dichiaro di aver letto e accettato l'
                                <a class="text-accent border-b" href="/privacy-policy" target="_blank">
                                    informativa sulla privacy
                                </a>
                                .
                            </label>
                        </div>
                    </div>

                    <button
                        class="rounded-sm bg-indigo-500 px-4 py-2 font-medium text-white hover:bg-indigo-600"
                        type="submit"
                    >
                        Invia
                    </button>
                </form>
            </div>
        @else
            <livewire:mason.form :$body :mail_to="$mail_to ?? null" />
        @endif
        @if ($buttons)
            <x-mason.buttons :buttons="$buttons" />
        @endif
    </div>
</x-mason.section>
