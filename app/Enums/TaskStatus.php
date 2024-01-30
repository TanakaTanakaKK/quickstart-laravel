<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskStatus extends Enum
{
    const PLANNED = 0;
    const IN_PROGRESS = 1;
    const COMPLETED = 2;

    public static function getDescription(mixed $value): string
    {
        return match ($value) {
            self::PLANNED => '新規',
            self::IN_PROGRESS => '着手',
            self::COMPLETED => '終了',
            default => '不明'
        };
    }
}
