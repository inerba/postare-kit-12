@props([
    'item' => null,
    'active' => false,
])

@php
    $itemClasses = 'leading-none px-3 py-2 mx-3 mt-2 text-gray-700 transition-colors duration-300 transform rounded-md hover:bg-gray-100 lg:mt-0 dark:text-gray-200 dark:hover:bg-gray-700';
@endphp

@if (isset($item['children']) && !empty($item['children']))
<x-menus.dropdown>
    <x-slot:trigger>
            {{ $item['label'] }}
        </x-slot>

        @foreach ($item['children'] as $child)
        <x-menus.main-menu.item :item="$child" class="px-4 py-4 mx-0 rounded-none" />
        @endforeach
</x-menus.dropdown>
@else
    @if (isset($item['url']))
    <a href="{{ $item['url'] }}"
        @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
        {{-- @if(isset($item['rel'])) rel="{{ $item['rel'] }}" @endif --}}
        @class([
            $itemClasses,
            'text-gray-500' => active_route($item['url']),
        ])
        ">
        {{ $item['label'] }}
    </a>
    @else
    <span @class([
        $itemClasses,
        'text-gray-500' => $active,
    ])>
        {{ $item['label'] }}
    </span>
    @endif
@endif
