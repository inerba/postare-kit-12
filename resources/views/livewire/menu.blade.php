<div @class([
    'hidden' => ! $menu,
])>
    <x-dynamic-component :component="'menus.' . $slug . $variant" :name="$menu->name" :items="$menu->items" />
</div>
