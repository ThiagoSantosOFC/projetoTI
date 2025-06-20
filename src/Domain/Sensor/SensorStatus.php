<?php
// src/Domain/Sensor/SensorStatus.php
declare(strict_types=1);

namespace App\Domain\Sensor;

class SensorStatus
{
    public const ACTIVE      = 'active';
    public const INACTIVE    = 'inactive';
    public const MAINTENANCE = 'maintenance';
    public const FAULT       = 'fault';
    public const UNKNOWN     = 'unknown';

    private static array $values = [
        self::ACTIVE,
        self::INACTIVE,
        self::MAINTENANCE,
        self::FAULT,
        self::UNKNOWN,
    ];

    public static function isValid(string $status): bool
    {
        return in_array($status, self::$values, true);
    }

    public static function fromString(string $status): string
    {
        $map = [
            'ativo'        => self::ACTIVE,
            'inativo'      => self::INACTIVE,
            'manutencao'   => self::MAINTENANCE,
            'falha'        => self::FAULT,
            'desconhecido' => self::UNKNOWN,
        ];

        return $map[$status] ?? self::UNKNOWN;
    }
}
