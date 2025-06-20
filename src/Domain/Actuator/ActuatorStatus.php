<?php
// src/Domain/Actuator/ActuatorStatus.php
namespace App\Domain\Actuator;

class ActuatorStatus
{
    public const ON = 'on';                 // Ligado
    public const OFF = 'off';               // Desligado
    public const ACTIVE = 'active';         // Ativo (estado operacional)
    public const INACTIVE = 'inactive';     // Inativo
    public const MAINTENANCE = 'maintenance'; // Em manutenção
    public const FAULT = 'fault';           // Com falha
    const UNKNOWN = 'unknown';           // Desconhecido

    private static array $values = [
        self::ON,
        self::OFF,
        self::ACTIVE,
        self::INACTIVE,
        self::MAINTENANCE,
        self::FAULT,
    ];

    public static function isValid(string $status): bool
    {
        return in_array($status, self::$values, true);
    }

    public static function fromString(string $status): ?string
    {
        $map = [
            'ligado'      => self::ON,
            'desligado'   => self::OFF,
            'ativo'       => self::ACTIVE,
            'inativo'     => self::INACTIVE,
            'manutencao'  => self::MAINTENANCE,
            'falha'       => self::FAULT,
        ];

        return $map[$status] ?? null;
    }
}
