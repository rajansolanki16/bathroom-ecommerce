<?php

namespace App\Enums;

enum ProductStatus: string
{
    case DRAFT = '0';
    case PUBLISHED = '1';
    case SCHEDULED = '2';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::SCHEDULED => 'Scheduled',
        };
    }
}
