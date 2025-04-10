<?php
// src/Domain/Sensor/SensorType.php
declare(strict_types=1);

namespace App\Domain\Sensor;

enum SensorType: string
{
    case TEMPERATURE = 'temperature';
    case HUMIDITY = 'humidity';
    case PRESSURE = 'pressure';
    case LIGHT = 'light';
    case MOTION = 'motion';
    case SMOKE = 'smoke';
    case GAS = 'gas';
    case WATER = 'water';
    case UNKNOWN = 'unknown';

    public static function isValid(string $type): bool
    {
        return in_array($type, array_column(self::cases(), 'value'));
    }

    public static function fromString(string $type): ?self
    {
        return match($type) {
            'temperatura'    => self::TEMPERATURE,
            'humidade'       => self::HUMIDITY,
            'pressao'        => self::PRESSURE,
            'luz'            => self::LIGHT,
            'movimento'      => self::MOTION,
            'fumo'           => self::SMOKE,
            'gas'            => self::GAS,
            'agua'           => self::WATER,
            'desconhecido'   => self::UNKNOWN,
            default => null
        };
    }
}