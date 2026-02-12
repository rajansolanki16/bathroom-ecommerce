<?php

namespace App\Enums;

enum ProductVisibility: string
{
    case HIDDEN = '0';
    case VISIBLE = '1';

    public function label(): string
    {
        return match ($this) {
            self::VISIBLE => 'Visible',
            self::HIDDEN => 'Hidden',
        };
    }
}
