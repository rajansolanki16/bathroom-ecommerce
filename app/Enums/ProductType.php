<?php

namespace App\Enums;

enum ProductType: string
{
    case SIMPLE = '0';
    case VARIANTS = '1';

    public function label(): string
    {
        return match ($this) {
            self::SIMPLE => 'Simple',
            self::VARIANTS => 'Variants',
        };
    }
}
