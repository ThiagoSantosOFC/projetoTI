<?php
// src/Domain/Sensor/Sensor.php
declare(strict_types=1);

namespace App\Domain\Sensor;

class Sensor
{
    private string $id;
    private string $name;
    private SensorType $type;
    private string $location;
    private SensorStatus $status;
    private array $metadata;
    private ?array $lastReading = null;

    public function __construct(
        string $id,
        string $name,
        SensorType $type,
        string $location,
        SensorStatus $status = SensorStatus::ACTIVE,
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

    public function getType(): SensorType
    {
        return $this->type;
    }

    public function setType(SensorType $type): void
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

    public function getStatus(): SensorStatus
    {
        return $this->status;
    }

    public function setStatus(SensorStatus $status): void
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
        // Adiciona timestamp se não foi fornecido
        if (!isset($reading['timestamp'])) {
            $reading['timestamp'] = time();
        }

        $this->lastReading = $reading;
    }

    /**
     * Ativa o sensor
     */
    public function activate(): void
    {
        $this->status = SensorStatus::ACTIVE;
    }

    /**
     * Desativa o sensor
     */
    public function deactivate(): void
    {
        $this->status = SensorStatus::INACTIVE;
    }

    /**
     * Marca o sensor como em manutenção
     */
    public function setMaintenance(): void
    {
        $this->status = SensorStatus::MAINTENANCE;
    }

    /**
     * Marca o sensor como com falha
     */
    public function setFault(): void
    {
        $this->status = SensorStatus::FAULT;
    }
}