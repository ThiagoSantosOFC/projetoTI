<?php
// src/Core/Actuator/ActuatorRepositoryInterface.php

namespace App\Core\Actuator;

use App\Domain\Actuator\Actuator;

interface ActuatorRepositoryInterface
{
    /**
     * Retorna todos os atuadores
     *
     * @return Actuator[]
     */
    public function findAll(): array;

    /**
     * Encontra um atuador pelo ID
     *
     * @param string $id ID do atuador
     * @return Actuator|null
     */
    public function findById(string $id): ?Actuator;

    /**
     * Salva um atuador
     *
     * @param Actuator $actuator
     * @return bool
     */
    public function save(Actuator $actuator): bool;

    /**
     * Remove um atuador
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Encontra atuadores por tipo
     *
     * @param string $type
     * @return Actuator[]
     */
    public function findByType(string $type): array;

    /**
     * Encontra atuadores por localização
     *
     * @param string $location
     * @return Actuator[]
     */
    public function findByLocation(string $location): array;
}