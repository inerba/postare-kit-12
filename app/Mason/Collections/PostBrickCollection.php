<?php

namespace App\Mason\Collections;

use App\Mason;

class PostBrickCollection
{
    /**
     * Restituisce un array di oggetti brick Mason.
     *
     * @return array<object>
     */
    public static function make(): array
    {
        return [
            Mason\Block::make(),
            Mason\Video::make(),
            Mason\Image::make(),
            Mason\Form::make(),
            Mason\Gallery::make(),
            Mason\Split::make(),
            Mason\SocialShare::make(),
            Mason\Accordion::make(),
            Mason\Code::make(),
            Mason\Reviews::make(),
        ];
    }
}
