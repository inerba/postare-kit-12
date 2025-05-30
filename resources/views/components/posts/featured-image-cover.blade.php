@props(['image_url' => false])

{{-- Featured image cover for posts --}}
@if ($image_url)
    {{-- <div class="aspect-[16/6] bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $image_url }}')"></div> --}}
    <div class="container mx-auto mt-16">
        {{-- Background image --}}
        <img src="{{ $image_url }}" alt="Featured Image" class="w-full" />
    </div>
@endif
