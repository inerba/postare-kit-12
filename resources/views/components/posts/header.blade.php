<div class="prose mx-auto mt-12 max-w-5xl text-center">
    <a href="{{ $category->permalink() }}" class="uppercase hover:underline">
        {{ $category->name }}
    </a>
    <h1>{{ $title }}</h1>
    <div class="font-bold uppercase">By {{ $author }}</div>
    <div class="text-sm capitalize">{{ $date }}</div>
</div>
