<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Prefecture extends Enum
{
    const HOKKAIDO = 1;
    const AOMORI = 2;
    const IWATE = 3;
    const MIYAGI = 4;
    const AKITA = 5;
    const YAMAGATA = 6;
    const FUKUSHIMA = 7;
    const IBARAKI = 8;
    const TOCHIGI = 9;
    const GUNMA = 10;
    const SAITAMA = 11;
    const CHIBA = 12;
    const TOKYO = 13;
    const KANAGAWA = 14;
    const NIIGATA = 15;
    const TOYAMA = 16;
    const ISHIKAWA = 17;
    const FUKUI = 18;
    const YAMANASHI = 19;
    const NAGANO = 20;
    const GIFU = 21;
    const SHIZUOKA = 22;
    const AICHI = 23;
    const MIE = 24;
    const SHIGA = 25;
    const KYOTO = 26;
    const OSAKA = 27;
    const HYOGO = 28;
    const NARA = 29;
    const WAKAYAMA = 30;
    const TOTTORI = 31;
    const SHIMANE = 32;
    const OKAYAMA = 33;
    const HIROSHIMA = 34;
    const YAMAGUCHI = 35;
    const TOKUSHIMA = 36;
    const KAGAWA = 37;
    const EHIME = 38;
    const KOCHI = 39;
    const FUKUOKA = 40;
    const SAGA = 41;
    const NAGASAKI = 42;
    const KUMAMOTO = 43;
    const OITA = 44;
    const MIYAZAKI = 45;
    const KAGOSHIMA = 46;
    const OKINAWA = 47;
    
    public static function getDescription(mixed $value): string
    {
        return match ($value) {
            self::HOKKAIDO => '北海道',
            self::AOMORI => '青森県',
            self::IWATE => '岩手県',
            self::MIYAGI => '宮城県',
            self::AKITA => '秋田県',
            self::YAMAGATA => '山形県',
            self::FUKUSHIMA => '福島県',
            self::IBARAKI => '茨城県',
            self::TOCHIGI => '栃木県',
            self::GUNMA => '群馬県',
            self::SAITAMA => '埼玉県',
            self::CHIBA => '千葉県',
            self::TOKYO => '東京都',
            self::KANAGAWA => '神奈川県',
            self::NIIGATA => '新潟県',
            self::TOYAMA => '富山県',
            self::ISHIKAWA => '石川県',
            self::FUKUI => '福井県',
            self::YAMANASHI => '山梨県',
            self::NAGANO => '長野県',
            self::GIFU => '岐阜県',
            self::SHIZUOKA => '静岡県',
            self::AICHI => '愛知県',
            self::MIE => '三重県',
            self::SHIGA => '滋賀県',
            self::KYOTO => '京都府',
            self::OSAKA => '大阪府',
            self::HYOGO => '兵庫県',
            self::NARA => '奈良県',
            self::WAKAYAMA => '和歌山県',
            self::TOTTORI => '鳥取県',
            self::SHIMANE => '島根県',
            self::OKAYAMA => '岡山県',
            self::HIROSHIMA => '広島県',
            self::YAMAGUCHI => '山口県',
            self::TOKUSHIMA => '徳島県',
            self::KAGAWA => '香川県',
            self::EHIME => '愛媛県',
            self::KOCHI => '高知県',
            self::FUKUOKA => '福岡県',
            self::SAGA => '佐賀県',
            self::NAGASAKI => '長崎県',
            self::KUMAMOTO => '熊本県',
            self::OITA => '大分県',
            self::MIYAZAKI => '宮崎県',
            self::KAGOSHIMA => '鹿児島県',
            self::OKINAWA => '沖縄県',
            default => '不明'
        };
    }
}
