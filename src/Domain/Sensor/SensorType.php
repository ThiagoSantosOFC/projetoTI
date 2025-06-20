<?php
// src/Domain/Sensor/SensorType.php
declare(strict_types=1);

namespace App\Domain\Sensor;

class SensorType
{
    public const TEMPERATURE = 'temperature';
    public const HUMIDITY    = 'humidity';
    public const PRESSURE    = 'pressure';
    public const LIGHT       = 'light';
    public const MOTION      = 'motion';
    public const SMOKE       = 'smoke';
    public const GAS         = 'gas';
    public const WATER       = 'water';
    public const UNKNOWN     = 'unknown';

    private static array $values = [
        self::TEMPERATURE,
        self::HUMIDITY,
        self::PRESSURE,
        self::LIGHT,
        self::MOTION,
        self::SMOKE,
        self::GAS,
        self::WATER,
        self::UNKNOWN,
    ];

    public static function isValid(string $type): bool
    {
        return in_array($type, self::$values, true);
    }

    public static function fromString(string $type): string
    {
        $map = [
            'temperatura'  => self::TEMPERATURE,
            'humidade'     => self::HUMIDITY,
            'pressao'      => self::PRESSURE,
            'luz'          => self::LIGHT,
            'movimento'    => self::MOTION,
            'fumo'         => self::SMOKE,
            'gas'          => self::GAS,
            'agua'         => self::WATER,
            'desconhecido' => self::UNKNOWN,
        ];

        return $map[$type] ?? self::UNKNOWN;
    }
}
