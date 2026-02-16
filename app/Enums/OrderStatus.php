<?php

namespace App\Enums;

enum OrderStatus: string
{
    case NEW_INQUIRY      = '0';
    case UNDER_REVIEW     = '1';
    case QUOTATION_SENT   = '2';
    case APPROVED_CLIENT  = '3';
    case PROCESSING       = '4';
    case COMPLETED        = '5';
    case CANCELLED        = '6';

    public function label(): string
    {
        return match ($this) {
            self::NEW_INQUIRY     => 'New Inquiry',
            self::UNDER_REVIEW    => 'Under Review',
            self::QUOTATION_SENT  => 'Quotation Sent',
            self::APPROVED_CLIENT => 'Approved by Client',
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
}
