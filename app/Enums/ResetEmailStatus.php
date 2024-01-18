<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ResetEmailStatus extends Enum
{
    const MAIL_SENT = 0;
    const COMPLETED = 1;

    public static function getDescription(mixed $value): string
    {
        return match ($value) {
            self::MAIL_SENT => 'メール送信完了',
            self::COMPLETED => '更新完了'
        };
    }
}
