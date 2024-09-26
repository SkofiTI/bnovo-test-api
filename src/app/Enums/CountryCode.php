<?php

namespace App\Enums;

enum CountryCode: string
{
    case RUSSIA = '+7';
    case USA = '+1';
    case UK = '+44';

    public static function fromPhoneNumber(string $phone): ?self
    {
        $cleanedPhone = ltrim($phone, '+');
        $cleanedPhone = ltrim($cleanedPhone, '0');

        foreach (self::cases() as $country) {
            $prefix = substr($country->value, 1);

            if (str_starts_with($cleanedPhone, $prefix)) {
                return $country;
            }
        }

        return null;
    }
}
