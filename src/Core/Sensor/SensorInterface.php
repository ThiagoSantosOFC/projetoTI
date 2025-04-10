<?php
// src/Core/Sensor/SensorInterface.php
declare(strict_types=1);

namespace App\Core\Sensor;

interface SensorInterface
{
    public function getId(): string;
    public function getName(): string;
    public function getType(): string;
    public function getLocation(): string;
    public function getStatus(): string;
    public function getLastReading(): ?array;
    public function getData(): array;
}