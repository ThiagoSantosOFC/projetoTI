<?php
// src/Core/Sensor/SensorRepositoryInterface.php
declare(strict_types=1);

namespace App\Core\Sensor;

interface SensorRepositoryInterface
{
    public function findAll(): array;
    public function findById(string $id): ?SensorInterface;
    public function save(SensorInterface $sensor): void;
    public function delete(string $id): void;
}