<?php
// src/Domain/Sensor/SensorStatus.php
declare(strict_types=1);

namespace App\Domain\Sensor;

enum SensorStatus: string
{
    case ACTIVE = 'ativo';
    case INACTIVE = 'inativo';
    case MAINTENANCE = 'manutencao';
    case UNKNOWN = 'desconhecido';
    case FAULT = 'falha';
    public static function isValid(string $status): bool
    {
        return in_array($status, array_column(self::cases(), 'value'));
    }

    public static function fromString(string $status): ?self
    {
        return match($status) {
            'ativo' => self::ACTIVE,
            'inativo' => self::INACTIVE,
            'manutencao' => self::MAINTENANCE,
            'desconhecido' => self::UNKNOWN,
            'falha' => self::FAULT,
            default => null
        };
    }
}