<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PROCESSING = '0';
    case COMPLETED = '1';
    case CANCELLED   = '2';




    public function label(): string
    {
        return match ($this) {
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
}
