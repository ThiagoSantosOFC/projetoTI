<?php
// src/Infrastructure/Sensor/InMemorySensorRepository.php
declare(strict_types=1);

namespace App\Infrastructure\Sensor;

use App\Core\Sensor\SensorInterface;
use App\Core\Sensor\SensorRepositoryInterface;

class InMemorySensorRepository implements SensorRepositoryInterface
{
    private array $sensors = [];

    public function findAll(): array
    {
        return array_values($this->sensors);
    }

    public function findById(string $id): ?SensorInterface
    {
        return $this->sensors[$id] ?? null;
    }

    public function save(SensorInterface $sensor): void
    {
        $this->sensors[$sensor->getId()] = $sensor;
    }

    public function delete(string $id): void
    {
        unset($this->sensors[$id]);
    }
}