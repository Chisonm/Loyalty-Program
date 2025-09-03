<?php

declare(strict_types=1);

namespace App\Enums;

enum RequirementType: string
{
    case PURCHASES_COUNT = 'purchases_count';
    case TOTAL_SPENT = 'total_spent';

    public function label(): string
    {
        return match ($this) {
            self::PURCHASES_COUNT => 'Number of Purchases',
            self::TOTAL_SPENT => 'Total Amount Spent',
        };
    }
}
