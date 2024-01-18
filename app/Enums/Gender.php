<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Gender extends Enum
{
    const MALE = 0;
    const FEMALE = 1;
    const OTHER = 2;

    public static function getDescription(mixed $value): string
    {
        return match ($value) {
            self::MALE => '男性',
            self::FEMALE => '女性',
            self::OTHER => 'その他',
            default => '不明'
        };
    }
}