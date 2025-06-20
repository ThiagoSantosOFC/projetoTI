<?php
// src/Service/ActuatorService.php

namespace App\Service;

use App\Core\Actuator\ActuatorRepositoryInterface;
use App\Domain\Actuator\Actuator;
use App\Domain\Actuator\ActuatorType;
use App\Domain\Actuator\ActuatorStatus;

class ActuatorService
{
    private ActuatorRepositoryInterface $repository;

    public function __construct(ActuatorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllActuators(): array
    {
        return $this->repository->findAll();
    }

    public function getActuatorById(string $id): ?Actuator
    {
        return $this->repository->findById($id);
    }

    public function createActuator(array $data): ?Actuator
    {
        if (empty($data['name']) || empty($data['location'])) {
            return null;
        }

        $id = $data['id'] ?? uniqid('act_');

        // Tipo do atuador
        $type = ActuatorType::UNKNOWN;
        if (!empty($data['type']) && ActuatorType::isValid($data['type'])) {
            $type = ActuatorType::fromString($data['type']);
        }

        // Status do atuador
        $status = ActuatorStatus::OFF;
        if (!empty($data['status']) && ActuatorStatus::isValid($data['status'])) {
            $status = ActuatorStatus::fromString($data['status']);
        }

        $actuator = new Actuator(
            $id,
            $data['name'],
            $type,
            $data['location'],
            $status,
            $data['metadata'] ?? []
        );

        return $this->repository->save($actuator) ? $actuator : null;
    }

    public function updateActuator(string $id, array $data): ?Actuator
    {
        $actuator = $this->repository->findById($id);
        if (!$actuator) {
            return null;
        }

        if (isset($data['name'])) {
            $actuator->setName($data['name']);
        }
        if (isset($data['location'])) {
            $actuator->setLocation($data['location']);
        }
        if (isset($data['type']) && ActuatorType::isValid($data['type'])) {
            $actuator->setType(ActuatorType::fromString($data['type']));
        }
        if (isset($data['status']) && ActuatorStatus::isValid($data['status'])) {
            $actuator->setStatus(ActuatorStatus::fromString($data['status']));
        }
        if (isset($data['metadata'])) {
            $actuator->setMetadata($data['metadata']);
        }

        return $this->repository->save($actuator) ? $actuator : null;
    }

    public function deleteActuator(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function turnOnActuator(string $id): ?Actuator
    {
        $actuator = $this->repository->findById($id);
        if (!$actuator) {
            return null;
        }
        $actuator->turnOn();
        return $this->repository->save($actuator) ? $actuator : null;
    }

    public function turnOffActuator(string $id): ?Actuator
    {
        $actuator = $this->repository->findById($id);
        if (!$actuator) {
            return null;
        }
        $actuator->turnOff();
        return $this->repository->save($actuator) ? $actuator : null;
    }

    public function getActuatorsByType(string $type): array
    {
        return $this->repository->findByType($type);
    }

    public function getActuatorsByLocation(string $location): array
    {
        return $this->repository->findByLocation($location);
    }
}
