<?php
// src/Core/Sensor/SensorServiceInterface.php
declare(strict_types=1);

namespace App\Core\Sensor;

interface SensorServiceInterface
{
    public function getAllSensors(): array;
    public function getSensorById(string $id): ?SensorInterface;
    public function createSensor(array $data): SensorInterface;
    public function updateSensor(string $id, array $data): ?SensorInterface;
    public function deleteSensor(string $id): bool;
    public function getSensorReading(string $id): ?array;
    public function simulateSensorReading(string $id, array $data): ?array;
}