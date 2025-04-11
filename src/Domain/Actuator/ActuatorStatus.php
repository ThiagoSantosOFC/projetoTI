<?php
// src/Domain/Actuator/ActuatorStatus.php

namespace App\Domain\Actuator;

enum ActuatorStatus: string
{
    case ON = 'on';                 // Ligado
    case OFF = 'off';               // Desligado
    case ACTIVE = 'active';         // Ativo (estado operacional)
    case INACTIVE = 'inactive';     // Inativo
    case MAINTENANCE = 'maintenance'; // Em manutenção
    case FAULT = 'fault';           // Com falha

    public static function isValid(string $status): bool
    {
        return in_array($status, array_column(self::cases(), 'value'));
    }

    public static function fromString(string $status): ?self
    {
        return match($status) {
            'ligado'       => self::ON,
            'desligado'    => self::OFF,
            'ativo'        => self::ACTIVE,
            'inativo'      => self::INACTIVE,
            'manutencao'   => self::MAINTENANCE,
            'falha'        => self::FAULT,
            default => null
        };
    }
}