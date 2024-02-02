<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CsvTaskColumn extends Enum
{
    const NAME = 0;
    const DETAIL = 1;
    const EXPIRED_AT = 2;
    const STATUS = 3;
}
