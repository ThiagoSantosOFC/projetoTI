<?php
// src/Domain/Actuator/Actuator.php
namespace App\Domain\Actuator;

use InvalidArgumentException;

class Actuator
{
    private string $id;
    private string $name;
    private string $type;       // string do ActuatorType::*
    private string $location;
    private string $status;     // string do ActuatorStatus::*
    private array $metadata;
    private ?array $lastAction = null;

    public function __construct(
        string $id,
        string $name,
        string $type,
        string $location,
        string $status = ActuatorStatus::OFF,
        array $metadata = []
    ) {
        if (!ActuatorType::isValid($type)) {
            throw new InvalidArgumentException("Tipo inválido para atuador: $type");
        }

        if (!ActuatorStatus::isValid($status)) {
            throw new InvalidArgumentException("Status inválido para atuador: $status");
        }

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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        if (!ActuatorType::isValid($type)) {
            throw new InvalidArgumentException("Tipo inválido para atuador: $type");
        }
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        if (!ActuatorStatus::isValid($status)) {
            throw new InvalidArgumentException("Status inválido para atuador: $status");
        }
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
        if (!isset($action['timestamp'])) {
            $action['timestamp'] = time();
        }
        $this->lastAction = $action;
    }

    public function turnOn(): void
    {
        $this->setStatus(ActuatorStatus::ON);
        $this->recordAction([
            'action' => 'turn_on',
            'result' => 'success'
        ]);
    }

    public function turnOff(): void
    {
        $this->setStatus(ActuatorStatus::OFF);
        $this->recordAction([
            'action' => 'turn_off',
            'result' => 'success'
        ]);
    }

    public function activate(): void
    {
        $this->setStatus(ActuatorStatus::ACTIVE);
        $this->recordAction([
            'action' => 'activate',
            'result' => 'success'
        ]);
    }

    public function deactivate(): void
    {
        $this->setStatus(ActuatorStatus::INACTIVE);
        $this->recordAction([
            'action' => 'deactivate',
            'result' => 'success'
        ]);
    }

    public function setMaintenance(): void
    {
        $this->setStatus(ActuatorStatus::MAINTENANCE);
        $this->recordAction([
            'action' => 'set_maintenance',
            'result' => 'success'
        ]);
    }

    public function setFault(): void
    {
        $this->setStatus(ActuatorStatus::FAULT);
        $this->recordAction([
            'action' => 'set_fault',
            'result' => 'success'
        ]);
    }

    public function getData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'location' => $this->location,
            'status' => $this->status,
            'metadata' => $this->metadata,
            'lastAction' => $this->lastAction
        ];
    }
}
