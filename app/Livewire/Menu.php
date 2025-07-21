<?php

namespace App\Livewire;

use App\Models\Menu as MenuModel;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Menu extends Component
{
    public ?MenuModel $menu = null;

    public ?string $slug = null;

    public ?string $variant = null;

    public function mount(): void
    {
        $cache_time = config('simple-menu-manager.menu_cache', 1);

        $this->menu = Cache::remember("menu_{$this->slug}", $cache_time, function () {
            return MenuModel::query()->where('slug', $this->slug)->first() ?? null;
        });
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        if (is_null($this->menu)) {
            return view('livewire.menu-not-found', ['slug' => $this->slug]);
        }

        return view('livewire.menu', ['menu' => $this->menu]);
    }
}
