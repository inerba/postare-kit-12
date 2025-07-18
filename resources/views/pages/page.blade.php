@php
    use Carbon\Carbon;
@endphp

@php
    $image = $page->getFirstMedia('featured_images');
@endphp

<x-layouts.main>
    <div class="post-content" data-aos="fade-up">
        <!-- ======= Single Post Content ======= -->
        <div class="prose max-w-none">
            <div class="mx-auto my-24 max-w-5xl text-balance text-center">
                <div class="text-muted text-sm">
                    <span>{{ Carbon::parse($page->updated_at)->format('D, d M Y') }}</span>
                </div>
                <h1 class="mb-5 text-center leading-normal">{{ $page->title }}</h1>
            </div>
            @if ($image && $page->extras['show_featured_image'])
                <div
                    class="main-column aspect-[16/6] bg-cover bg-center bg-no-repeat"
                    style="background-image: url('{{ $image->getUrl() }}')"
                ></div>
            @endif

            <x-page :content="$page->content" :data="$page" />
        </div>
        <!-- End Single Post Content -->

        <hr />
    </div>
</x-layouts.main>
