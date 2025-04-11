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

    /**
     * Obtém todos os atuadores
     */
    public function getAllActuators(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Obtém um atuador pelo ID
     */
    public function getActuatorById(string $id): ?Actuator
    {
        return $this->repository->findById($id);
    }

    /**
     * Cria um novo atuador
     */
    public function createActuator(array $data): ?Actuator
    {
        // Validar dados
        if (empty($data['name']) || empty($data['location'])) {
            return null;
        }

        // Gerar ID se não fornecido
        $id = $data['id'] ?? uniqid('act_');

        // Tipo do atuador
        $type = ActuatorType::UNKNOWN;
        if (!empty($data['type']) && ActuatorType::isValid($data['type'])) {
            $type = ActuatorType::from($data['type']);
        }

        // Status do atuador
        $status = ActuatorStatus::OFF;
        if (!empty($data['status']) && ActuatorStatus::isValid($data['status'])) {
            $status = ActuatorStatus::from($data['status']);
        }

        $actuator = new Actuator(
            $id,
            $data['name'],
            $type,
            $data['location'],
            $status,
            $data['metadata'] ?? []
        );

        if ($this->repository->save($actuator)) {
            return $actuator;
        }

        return null;
    }

    /**
     * Atualiza um atuador existente
     */
    public function updateActuator(string $id, array $data): ?Actuator
    {
        $actuator = $this->repository->findById($id);

        if (!$actuator) {
            return null;
        }

        // Atualiza propriedades
        if (isset($data['name'])) {
            $actuator->setName($data['name']);
        }

        if (isset($data['location'])) {
            $actuator->setLocation($data['location']);
        }

        if (isset($data['type']) && ActuatorType::isValid($data['type'])) {
            $actuator->setType(ActuatorType::from($data['type']));
        }

        if (isset($data['status']) && ActuatorStatus::isValid($data['status'])) {
            $actuator->setStatus(ActuatorStatus::from($data['status']));
        }

        if (isset($data['metadata'])) {
            $actuator->setMetadata($data['metadata']);
        }

        if ($this->repository->save($actuator)) {
            return $actuator;
        }

        return null;
    }

    /**
     * Remove um atuador
     */
    public function deleteActuator(string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Liga um atuador
     */
    public function turnOnActuator(string $id): ?Actuator
    {
        $actuator = $this->repository->findById($id);

        if (!$actuator) {
            return null;
        }

        $actuator->turnOn();

        if ($this->repository->save($actuator)) {
            return $actuator;
        }

        return null;
    }

    /**
     * Desliga um atuador
     */
    public function turnOffActuator(string $id): ?Actuator
    {
        $actuator = $this->repository->findById($id);

        if (!$actuator) {
            return null;
        }

        $actuator->turnOff();

        if ($this->repository->save($actuator)) {
            return $actuator;
        }

        return null;
    }

    /**
     * Obtém atuadores por tipo
     */
    public function getActuatorsByType(string $type): array
    {
        return $this->repository->findByType($type);
    }

    /**
     * Obtém atuadores por localização
     */
    public function getActuatorsByLocation(string $location): array
    {
        return $this->repository->findByLocation($location);
    }
}