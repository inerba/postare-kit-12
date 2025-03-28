<?php

namespace App\Mason;

class BrickCollection
{
    public static function make(): array
    {
        return [
            // Hero::make(),
            Block::make(),
            Video::make(),
            Image::make(),
            // Gallery::make(),
            Split::make(),
            SocialShare::make(),
            // Section::make(),
            // Chatbot::make(),
            // Reviews::make(),
        ];
    }
}
