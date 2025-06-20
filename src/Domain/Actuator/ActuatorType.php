<?php
// src/Domain/Actuator/ActuatorType.php
namespace App\Domain\Actuator;

class ActuatorType
{
    public const GATE = 'gate';             // Portão de estacionamento
    public const LIGHT = 'light';           // Iluminação
    public const BARRIER = 'barrier';       // Barreira
    public const HEATING = 'heating';         // Aquecimento
    public const COOLING = 'cooling';         // Resfriamento
    public const VENTILATION = 'ventilation'; // Ventilação
    public const ALARM = 'alarm';           // Alarme
    public const DISPLAY = 'display';       // Painel de informação
    public const HVAC = 'hvac';             // Sistema de ventilação
    public const SPRINKLER = 'sprinkler';   // Sistema de aspersão
    public const UNKNOWN = 'unknown';       // Desconhecido

    private static array $values = [
        self::GATE,
        self::LIGHT,
        self::BARRIER,
        self::HEATING,
        self::COOLING,
        self::VENTILATION,
        self::ALARM,
        self::DISPLAY,
        self::HVAC,
        self::SPRINKLER,
        self::UNKNOWN,
    ];

    public static function isValid(string $type): bool
    {
        return in_array($type, self::$values, true);
    }

    public static function fromString(string $type): ?string
    {
        $map = [
            'portao'       => self::GATE,
            'luz'          => self::LIGHT,
            'barreira'     => self::BARRIER,
            'aquecimento'  => self::HEATING,
            'resfriamento' => self::COOLING,
            'ventilacao'   => self::VENTILATION,
            'alarme'       => self::ALARM,
            'painel'       => self::DISPLAY,
            'hvac'         => self::HVAC,
            'aspersor'     => self::SPRINKLER,
            'desconhecido' => self::UNKNOWN,
        ];

        return $map[$type] ?? null;
    }
}
