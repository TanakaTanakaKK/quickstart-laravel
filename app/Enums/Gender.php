<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Gender extends Enum
{
    const MALE = 0;
    const FEMALE = 1;
    /**
     * @inheritDoc
     */
    public static function getValues($keys = null): array
    {
        return [
            self::MALE => '男性',
            self::FEMALE => '女性',
        ];
    }
}