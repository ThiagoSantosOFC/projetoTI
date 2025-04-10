<?php
// src/Infrastructure/Sensor/SensorSimulator.php
declare(strict_types=1);

namespace App\Infrastructure\Sensor;

use App\Core\Sensor\SensorInterface;

class SensorSimulator
{
    public function generateReading(SensorInterface $sensor): array
    {
        switch ($sensor->getType()) {
            case 'presenca':
                return [
                    'occupied' => (bool)random_int(0, 1),
                    'confidence' => random_int(85, 100),
                ];
            case 'proximidade':
                return [
                    'distance' => random_int(10, 500) / 10,
                    'unit' => 'cm',
                ];
            case 'temperatura':
                return [
                    'value' => random_int(150, 350) / 10,
                    'unit' => '°C',
                ];
            case 'humidade':
                return [
                    'value' => random_int(30, 90),
                    'unit' => '%',
                ];
            default:
                return [
                    'value' => random_int(0, 100),
                    'unit' => 'generic',
                ];
        }
    }
}