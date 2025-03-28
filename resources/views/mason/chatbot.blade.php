@php
    $props = array_merge(['content' => null], \App\Mason\Macro\Theme::getProps(), \App\Mason\Macro\SectionHeader::getProps());
@endphp

@props($props)

<x-mason.section :theme="$theme">
    <x-mason.header :title="$header_title" :subtitle="$header_tagline" :align="$header_align" />
    {{--  --}}
    <div class="not-prose">
        <div class="mx-auto flex w-full max-w-5xl flex-col gap-4 rounded-lg border bg-white p-8 shadow-xl">
            <!-- Recieved -->
            <div class="flex items-end gap-2 text-black">
                <img class="size-8 rounded-full object-cover" src="https://penguinui.s3.amazonaws.com/component-assets/avatar-8.webp" alt="avatar" />
                <div class="bg-primary-50 rounded-r-radius rounded-tl-radius bg-surface-alt text-on-surface mr-auto flex max-w-[70%] flex-col gap-2 p-4 md:max-w-[60%]">
                    <span class="font-semibold">Agente</span>
                    <div class="text-sm">
                        Ciao, vorresti sapere quanto vale il tuo immobile?
                    </div>
                    <span class="ml-auto text-xs">11:32 AM</span>
                </div>
            </div>

            <div class="flex items-end gap-2 text-white">
                <div class="bg-primary-900 rounded-l-radius rounded-tr-radius bg-primary text-on-primary ml-auto flex max-w-[70%] flex-col gap-2 p-4 text-sm md:max-w-[60%]">
                    Si grazie
                    <span class="ml-auto text-xs">11:34 AM</span>
                </div>
                <span class="border-outline bg-casa-blu-700 flex size-8 items-center justify-center overflow-hidden rounded-full border text-sm font-bold tracking-wider text-white">TU</span>
            </div>

            <div class="flex items-end gap-2 text-black">
                <img class="size-8 rounded-full object-cover" src="https://penguinui.s3.amazonaws.com/component-assets/avatar-8.webp" alt="avatar" />
                <div class="bg-primary-50 rounded-r-radius rounded-tl-radius bg-surface-alt text-on-surface nndark:bg-surface-dark-alt nndark:text-on-surface-dark mr-auto flex max-w-[70%] flex-col gap-2 p-4 md:max-w-[60%]">
                    <span class="text-on-surface-strong nndark:text-on-surface-dark-strong font-semibold">Agente</span>
                    <div class="text-sm">
                        Perfetto, allora ti chiedo alcune informazioni.
                    </div>
                    <div class="text-sm">
                        Qual è l'indirizzo del tuo immobile?
                    </div>
                    <span class="ml-auto text-xs">11:32 AM</span>
                </div>
            </div>

            <div class="relative w-full">
                <label for="aiPromt" for="aiPromt" class="sr-only">ai prompt</label>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" class="nndark:fill-white absolute left-3 top-1/2 size-4 -translate-y-1/2 fill-black">
                    <path fill-rule="evenodd"
                        d="M5 4a.75.75 0 0 1 .738.616l.252 1.388A1.25 1.25 0 0 0 6.996 7.01l1.388.252a.75.75 0 0 1 0 1.476l-1.388.252A1.25 1.25 0 0 0 5.99 9.996l-.252 1.388a.75.75 0 0 1-1.476 0L4.01 9.996A1.25 1.25 0 0 0 3.004 8.99l-1.388-.252a.75.75 0 0 1 0-1.476l1.388-.252A1.25 1.25 0 0 0 4.01 6.004l.252-1.388A.75.75 0 0 1 5 4ZM12 1a.75.75 0 0 1 .721.544l.195.682c.118.415.443.74.858.858l.682.195a.75.75 0 0 1 0 1.442l-.682.195a1.25 1.25 0 0 0-.858.858l-.195.682a.75.75 0 0 1-1.442 0l-.195-.682a1.25 1.25 0 0 0-.858-.858l-.682-.195a.75.75 0 0 1 0-1.442l.682-.195a1.25 1.25 0 0 0 .858-.858l.195-.682A.75.75 0 0 1 12 1ZM10 11a.75.75 0 0 1 .728.568.968.968 0 0 0 .704.704.75.75 0 0 1 0 1.456.968.968 0 0 0-.704.704.75.75 0 0 1-1.456 0 .968.968 0 0 0-.704-.704.75.75 0 0 1 0-1.456.968.968 0 0 0 .704-.704A.75.75 0 0 1 10 11Z"
                        clip-rule="evenodd" />
                </svg>
                <input id="aiPromt" type="text"
                    class="border-outline bg-neutral-50! nndark:border-neutral-700 nndark:bg-neutral-900/50 nndark:text-neutral-300 nndark:focus-visible:outline-white w-full rounded-sm border border-neutral-300 px-2 py-2 pl-10 pr-24 text-sm text-neutral-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75"
                    name="prompt" placeholder="Rispondi ..." />
                <button type="button"
                    class="nndark:bg-white nndark:text-black nndark:focus-visible:outline-white absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer rounded-sm bg-black px-2 py-1 text-xs tracking-wide text-neutral-100 transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0">Invia</button>
            </div>
        </div>
    </div>
    {{--  --}}
    @if ($buttons)
        <x-mason.buttons :buttons="$buttons" />
    @endif
</x-mason.section>
