<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self customer()
 * @method static self admin()
 */
class UserRoleEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'customer' => 1,
            'admin' => 2,
        ];
    }
}
