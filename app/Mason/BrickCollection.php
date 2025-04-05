<?php

namespace App\Mason;

class BrickCollection
{
    public static function make(): array
    {
        return [
            Block::make(),
            Video::make(),
            Image::make(),
            Form::make(),
            Gallery::make(),
            Split::make(),
            SocialShare::make(),
            Accordion::make(),
            Code::make(),
            Reviews::make(),
        ];
    }
}
