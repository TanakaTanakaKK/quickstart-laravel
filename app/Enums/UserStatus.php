<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserStatus extends Enum
{
    const PENDING = 0;
    const APPROVED = 1;
    const COMPLETED = 2;
    public static function getDescription(mixed $value): string
    {
        return match ($value){
            self::PENDING => 'メール送信完了',
            self::APPROVED => '承認済み',
            self::COMPLETED => '本登録完了',
        };
    }
}
