<?php
// src/Domain/Actuator/Actuator.php

namespace App\Domain\Actuator;

class Actuator
{
    private string $id;
    private string $name;
    private ActuatorType $type;
    private string $location;
    private ActuatorStatus $status;
    private array $metadata;
    private ?array $lastAction = null;

    public function __construct(
        string $id,
        string $name,
        ActuatorType $type,
        string $location,
        ActuatorStatus $status = ActuatorStatus::OFF,
        array $metadata = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->location = $location;
        $this->status = $status;
        $this->metadata = $metadata;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): ActuatorType
    {
        return $this->type;
    }

    public function setType(ActuatorType $type): void
    {
        $this->type = $type;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getStatus(): ActuatorStatus
    {
        return $this->status;
    }

    public function setStatus(ActuatorStatus $status): void
    {
        $this->status = $status;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function hasLastAction(): bool
    {
        return $this->lastAction !== null;
    }

    public function getLastAction(): ?array
    {
        return $this->lastAction;
    }

    public function recordAction(array $action): void
    {
        // Adiciona timestamp se não foi fornecido
        if (!isset($action['timestamp'])) {
            $action['timestamp'] = time();
        }

        $this->lastAction = $action;
    }

    /**
     * Liga o atuador
     */
    public function turnOn(): void
    {
        $this->status = ActuatorStatus::ON;
        $this->recordAction([
            'action' => 'turn_on',
            'result' => 'success'
        ]);
    }

    /**
     * Desliga o atuador
     */
    public function turnOff(): void
    {
        $this->status = ActuatorStatus::OFF;
        $this->recordAction([
            'action' => 'turn_off',
            'result' => 'success'
        ]);
    }

    /**
     * Ativa o atuador
     */
    public function activate(): void
    {
        $this->status = ActuatorStatus::ACTIVE;
        $this->recordAction([
            'action' => 'activate',
            'result' => 'success'
        ]);
    }

    /**
     * Desativa o atuador
     */
    public function deactivate(): void
    {
        $this->status = ActuatorStatus::INACTIVE;
        $this->recordAction([
            'action' => 'deactivate',
            'result' => 'success'
        ]);
    }

    /**
     * Marca o atuador como em manutenção
     */
    public function setMaintenance(): void
    {
        $this->status = ActuatorStatus::MAINTENANCE;
        $this->recordAction([
            'action' => 'set_maintenance',
            'result' => 'success'
        ]);
    }

    /**
     * Marca o atuador como com falha
     */
    public function setFault(): void
    {
        $this->status = ActuatorStatus::FAULT;
        $this->recordAction([
            'action' => 'set_fault',
            'result' => 'success'
        ]);
    }
}