<?php namespace App\Enums;

enum GenderEnum: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';

    public static function getAll(): array
    {
        return [
            self::MALE->value,
            self::FEMALE->value,
            self::OTHER->value,
        ];
    }

}