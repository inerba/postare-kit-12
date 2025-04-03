<div>
    @if ($contactFormSubmitted)
        <div
            wire:loading.remove
            wire:target="sendEmail"
            class="mb-4 flex items-center gap-2 border-green-800 bg-green-50 p-4 text-center text-2xl text-green-900"
        >
            <x-heroicon-s-check class="h-12 w-12" />
            <span>Messaggio inviato con successo!</span>
        </div>
    @else
        <form wire:submit.prevent="sendEmail">
            <div class="mb-4">
                <label class="mb-2 block font-medium text-gray-700" for="name">Nome</label>
                <input class="w-full border border-gray-400 p-2" type="text" id="name" wire:model.lazy="name" />
                @error('name')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div class="">
                    <label class="mb-2 block font-medium text-gray-700" for="email">Email</label>
                    <input class="w-full border border-gray-400 p-2" type="email" id="email" wire:model.lazy="email" />
                    @error('email')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label class="mb-2 block font-medium text-gray-700" for="phone">Telefono</label>
                    <input class="w-full border border-gray-400 p-2" type="text" id="phone" wire:model.lazy="phone" />
                    @error('phone')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="mb-2 block font-medium text-gray-700" for="body">Messaggio</label>
                <textarea class="h-32 w-full border border-gray-400 p-2" id="body" wire:model.lazy="body"></textarea>
                @error('body')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <div class="flex items-center">
                    <input wire:model="gdprConsent" type="checkbox" class="form-checkbox" id="gdprConsent" required />
                    <label class="ml-2 text-sm text-gray-700" for="gdprConsent">
                        Dichiaro di aver letto e accettato l'
                        <a class="text-primary border-b" href="/privacy-policy" target="_blank">
                            informativa sulla privacy
                        </a>
                        .
                    </label>
                </div>
                @error('gdprConsent')
                    <p class="mt-4 text-xs italic text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            @if ($errorMessage)
                <div
                    wire:loading.remove
                    wire:target="sendEmail"
                    class="mb-4 flex items-center gap-2 border-rose-800 bg-rose-50 p-4 text-center text-sm text-rose-900"
                >
                    {{ $errorMessage }}
                </div>
            @endif

            {{-- hidden input isNlt --}}
            <input type="hidden" wire:model="isNlt" value="$isNlt" />

            <button
                wire:target="sendEmail"
                wire.click="sendEmail"
                class="bg-accent hover:bg-accent/80 rounded-sm px-4 py-2 font-medium text-white"
                type="submit"
            >
                <svg
                    class="mr-2 size-5 animate-spin text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    wire:loading
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                <div wire:loading.remove wire:target="sendEmail">Invia</div>
                <div wire:loading wire:target="sendEmail">Invio in corso...</div>
            </button>
        </form>
    @endif
</div>
