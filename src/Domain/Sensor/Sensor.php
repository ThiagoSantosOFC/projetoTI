<?php
// src/Domain/Sensor/Sensor.php
declare(strict_types=1);

namespace App\Domain\Sensor;

use App\Core\Sensor\SensorInterface;

class Sensor implements SensorInterface
{
    private string $id;
    private string $name;
    private string $type;      // string de SensorType::*
    private string $location;
    private string $status;    // string de SensorStatus::*
    private array  $metadata;
    private ?array $lastReading = null;

    public function __construct(
        string $id,
        string $name,
        string $type,
        string $location,
        string $status = SensorStatus::ACTIVE,
        array  $metadata = []
    ) {
        // Validações básicas
        if (!SensorType::isValid($type)) {
            $type = SensorType::UNKNOWN;
        }
        if (!SensorStatus::isValid($status)) {
            $status = SensorStatus::UNKNOWN;
        }

        $this->id       = $id;
        $this->name     = $name;
        $this->type     = $type;
        $this->location = $location;
        $this->status   = $status;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getLastReading(): ?array
    {
        return $this->lastReading;
    }

    public function getData(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'type'        => $this->type,
            'location'    => $this->location,
            'status'      => $this->status,
            'metadata'    => $this->metadata,
            'lastReading' => $this->lastReading,
        ];
    }
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setType(string $type): void
    {
        $this->type = SensorType::isValid($type) ? $type : SensorType::UNKNOWN;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function setStatus(string $status): void
    {
        $this->status = SensorStatus::isValid($status) ? $status : SensorStatus::UNKNOWN;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function hasReading(): bool
    {
        return $this->lastReading !== null;
    }

    public function getReading(): ?array
    {
        return $this->lastReading;
    }

    public function updateReading(array $reading): void
    {
        if (!isset($reading['timestamp'])) {
            $reading['timestamp'] = time();
        }
        $this->lastReading = $reading;
    }

    public function activate(): void
    {
        $this->status = SensorStatus::ACTIVE;
    }

    public function deactivate(): void
    {
        $this->status = SensorStatus::INACTIVE;
    }

    public function setMaintenance(): void
    {
        $this->status = SensorStatus::MAINTENANCE;
    }

    public function setFault(): void
    {
        $this->status = SensorStatus::FAULT;
    }
}
