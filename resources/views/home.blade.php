<x-layouts.main>
    <x-slot:seo>
        <x-seo :title="db_config('homepage.meta.seo.tag_title')" :description="db_config('homepage.meta.seo.meta_description')" :og_title="db_config('homepage.social.og.title')" :og_description="db_config('homepage.social.og.description')" :image="db_config('homepage.social.og_image')" />
    </x-slot>
    <livewire:slider :slides="db_config('homepage.slides')" :settings="db_config('homepage.slider_settings')" />
    <x-page :content="db_config('homepage.content')" />
</x-layouts.main>

