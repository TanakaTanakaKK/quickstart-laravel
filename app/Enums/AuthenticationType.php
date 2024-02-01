<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AuthenticationType extends Enum
{
    const USER_REGISTER = 0;
    const PASSWORD_RESET = 1;
    const EMAIL_RESET =2;
    const OTHER = 3;
}
