<x-layouts.main>
    <livewire:slider :slides="db_config('homepage.slides')" :settings="db_config('homepage.slider_settings')" />
    <x-page :content="db_config('homepage.content')" />
</x-layouts.main>
