<?php
namespace App\Domain\Actuator;

enum ActuatorType: string
{
    case GATE = 'gate';             // Portão de estacionamento
    case LIGHT = 'light';           // Iluminação
    case BARRIER = 'barrier';       // Barreira
    case HEATING = 'heating';         // Aquecimento
    case COOLING = 'cooling';         // Resfriamento
    case VENTILATION = 'ventilation'; // Ventilação
    case ALARM = 'alarm';           // Alarme
    case DISPLAY = 'display';       // Painel de informação
    case HVAC = 'hvac';             // Sistema de ventilação
    case SPRINKLER = 'sprinkler';   // Sistema de aspersão
    case UNKNOWN = 'unknown';       // Desconhecido

    public static function isValid(string $type): bool
    {
        return in_array($type, array_column(self::cases(), 'value'));
    }

    public static function fromString(string $type): ?self
    {
        return match($type) {
            'portao'       => self::GATE,
            'luz'          => self::LIGHT,
            'barreira'     => self::BARRIER,
            'alarme'       => self::ALARM,
            'painel'       => self::DISPLAY,
            'ventilacao'   => self::HVAC,
            'aspersor'     => self::SPRINKLER,
            'desconhecido' => self::UNKNOWN,
            default => null
        };
    }
}