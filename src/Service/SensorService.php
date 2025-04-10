<?php
// src/Service/SensorService.php
declare(strict_types=1);

namespace App\Service;

use App\Core\Sensor\SensorInterface;
use App\Core\Sensor\SensorRepositoryInterface;
use App\Core\Sensor\SensorServiceInterface;
use App\Domain\Sensor\Sensor;
use App\Domain\Sensor\SensorStatus;
use App\Domain\Sensor\SensorType;
use App\Infrastructure\Sensor\SensorSimulator;

class SensorService implements SensorServiceInterface
{
    private SensorRepositoryInterface $repository;
    private SensorSimulator $simulator;

    public function __construct(
        SensorRepositoryInterface $repository,
        SensorSimulator $simulator
    ) {
        $this->repository = $repository;
        $this->simulator = $simulator;
    }

    public function getAllSensors(): array
    {
        $sensors = $this->repository->findAll();
        return array_map(fn(SensorInterface $sensor) => $sensor->getData(), $sensors);
    }

    public function getSensorById(string $id): ?SensorInterface
    {
        return $this->repository->findById($id);
    }

    public function createSensor(array $data): SensorInterface
    {
        // Converte os tipos enumerados corretamente
        $type = isset($data['type']) ? SensorType::tryFrom($data['type']) ?? SensorType::UNKNOWN : SensorType::UNKNOWN;
        $status = isset($data['status']) ? SensorStatus::tryFrom($data['status']) ?? SensorStatus::ACTIVE : SensorStatus::ACTIVE;

        // Gera um ID se não foi fornecido
        if (empty($data['id'])) {
            $data['id'] = uniqid('sensor_');
        }

        $sensor = new Sensor(
            $data['id'],
            $data['name'],
            $type,
            $data['location'],
            $status,
            $data['metadata'] ?? []
        );

        $this->repository->save($sensor);
        return $sensor;
    }

    public function updateSensor(string $id, array $data): ?SensorInterface
    {
        $sensor = $this->repository->findById($id);

        if (!$sensor) {
            return null;
        }

        if ($sensor instanceof Sensor) {
            // Atualize cada campo se estiver presente nos dados
            if (isset($data['name'])) {
                $sensor->setName($data['name']);
            }
            if (isset($data['location'])) {
                $sensor->setLocation($data['location']);
            }
            if (isset($data['type'])) {
                $type = SensorType::tryFrom($data['type']) ?? $sensor->getType();
                $sensor->setType($type);
            }
            if (isset($data['status'])) {
                $status = SensorStatus::tryFrom($data['status']) ?? $sensor->getStatus();
                $sensor->setStatus($status);
            }
            if (isset($data['metadata'])) {
                $sensor->setMetadata($data['metadata']);
            }

            $this->repository->save($sensor);
        }

        return $sensor;
    }

    public function deleteSensor(string $id): bool
    {
        $sensor = $this->repository->findById($id);

        if (!$sensor) {
            return false;
        }

        $this->repository->delete($id);
        return true;
    }

    public function getSensorReading(string $id): ?array
    {
        $sensor = $this->repository->findById($id);

        if (!$sensor) {
            return null;
        }

        if ($sensor->getStatus() !== SensorStatus::ACTIVE) {
            return ['error' => 'Sensor não está ativo'];
        }

        $reading = $this->simulator->generateReading($sensor);

        if ($sensor instanceof Sensor) {
            $sensor->updateReading($reading);
            $this->repository->save($sensor);
        }

        return $reading;
    }

    public function simulateSensorReading(string $id, array $data): ?array
    {
        $sensor = $this->repository->findById($id);

        if (!$sensor) {
            return null;
        }

        if ($sensor->getStatus() !== SensorStatus::ACTIVE) {
            return ['error' => 'Sensor não está ativo'];
        }

        if ($sensor instanceof Sensor) {
            $sensor->updateReading($data);
            $this->repository->save($sensor);
        }

        return $data;
    }
}