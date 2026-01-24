<?php

namespace App\Enums;

enum ProductVisibility: string
{
    case PUBLIC = '0';
    case HIDDEN = '1';

    public function label(): string
    {
        return match ($this) {
            self::PUBLIC => 'Public',
            self::HIDDEN => 'Hidden',
        };
    }
}
