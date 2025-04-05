<x-layouts.main>
    <livewire:slider :slides="db_config('homepage.slides')" :settings="db_config('homepage.slider_settings')" />
    <div>
        <div class="flex cursor-pointer flex-col items-center gap-2">
            <div class="border-2 border-black bg-transparent p-2 leading-none text-black hover:bg-gray-100">Testo</div>
            Bordo
        </div>
    </div>
</x-layouts.main>
