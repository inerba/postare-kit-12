<?php

namespace App\Livewire;

use Livewire\Component;

class Slider extends Component
{
    /**
     * Le slide del componente Slider.
     *
     * @var array<string, mixed>
     */
    public array $slides;

    /**
     * Impostazioni del componente Slider.
     *
     * @var array<string, mixed>
     */
    public array $settings = [];

    // settings
    public bool $pagination = true;

    public int $perPage = 1;

    public bool $arrows = true;

    public bool $rewind = true;

    public string $carouselGap = '0.5rem';

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.slider');
    }
}
