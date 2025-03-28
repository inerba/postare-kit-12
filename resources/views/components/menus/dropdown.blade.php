@props([
    'maxHeight' => null,
    'offset' => 8,
    'placement' => 'bottom-start',
    'shift' => false,
    'teleport' => false,
    'trigger' => null,
    'width' => null,
])

@php
    use Filament\Support\Enums\MaxWidth;
@endphp

<div x-data="{
    submenuOpen: false,
    toggle: function(event) {
        this.submenuOpen = !this.submenuOpen
    },
    open: function(event) {
        this.submenuOpen = true
    },
    close: function(event) {
        this.submenuOpen = false
    },
}" {{ $attributes->class(['dropdown relative']) }}>
    <div x-on:click="toggle" {{ $trigger->attributes->class(['dropdown-trigger flex cursor-pointer']) }}>
        {{ $trigger }}
    </div>
    <div x-cloak @click.outside="submenuOpen = false" x-show="submenuOpen"
        x-float{{ $placement ? ".placement.{$placement}" : '' }}.flip{{ $shift ? '.shift' : '' }}{{ $teleport ? '.teleport' : '' }}{{ $offset ? '.offset' : '' }}="{ offset: {{ $offset }} }" x-ref="panel" x-transition:enter-start="opacity-0"
        x-transition:leave-end="opacity-0" @class([
            'dropdown-panel absolute z-10 w-auto divide-y divide-neutral-100 rounded-lg bg-white shadow-lg ring-1 ring-neutral-950/5 transition',
        ]) @style([
            "max-height: {$maxHeight}" => $maxHeight,
        ])>
        {{ $slot }}
    </div>
</div>
