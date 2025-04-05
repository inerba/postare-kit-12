<?php

namespace App\Livewire;

use Livewire\Component;

class Slider extends Component
{
    public $slides = [];

    public array $settings = [];

    // settings
    public bool $pagination = true;

    public int $perPage = 1;

    public bool $arrows = true;

    public bool $rewind = true;

    public string $carouselGap = '0.5rem';

    public function mount()
    {
        $this->slides = collect($this->slides);
    }

    public function render()
    {
        return view('livewire.slider');
    }
}
