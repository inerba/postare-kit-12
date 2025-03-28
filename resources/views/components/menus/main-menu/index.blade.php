@props([
    'name' => null,
    'items' => [],
])

<div class="-mx-6 flex flex-col lg:mx-8 lg:flex-row lg:items-center">
    @foreach ($items as $item)
        <x-menus.main-menu.item :item="$item" />
    @endforeach
</div>
