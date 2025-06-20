<?php
// src/Infrastructure/Sensor/JsonSensorRepository.php
declare(strict_types=1);

namespace App\Infrastructure\Sensor;

use App\Core\Sensor\SensorInterface;
use App\Core\Sensor\SensorRepositoryInterface;
use App\Domain\Sensor\Sensor;
use App\Domain\Sensor\SensorStatus;
use App\Domain\Sensor\SensorType;

class JsonSensorRepository implements SensorRepositoryInterface
{
    private string $filePath;
    /** @var Sensor[] */
    private array $sensors = [];

    /**
     * @param string $filePath Caminho para o arquivo JSON dos sensores
     */
    public function __construct(string $filePath = __DIR__ . '/../../data/sensor.json')
    {
        $this->filePath = $filePath;
        $this->loadSensors();
    }

    /**
     * Carrega sensores do arquivo JSON
     */
    private function loadSensors(): void
    {
        if (!file_exists($this->filePath)) {
            $directory = dirname($this->filePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            file_put_contents($this->filePath, json_encode([]));
            return;
        }

        $jsonContent = file_get_contents($this->filePath);
        if (empty($jsonContent)) {
            return;
        }

        $data = json_decode($jsonContent, true);
        if (!is_array($data)) {
            return;
        }

        foreach ($data as $item) {
            // Tipo
            $rawType = $item['type'] ?? SensorType::UNKNOWN;
            $type = SensorType::isValid($rawType) ? $rawType : SensorType::UNKNOWN;

            // Status
            $rawStatus = $item['status'] ?? SensorStatus::UNKNOWN;
            $status = SensorStatus::isValid($rawStatus) ? $rawStatus : SensorStatus::UNKNOWN;

            // Cria instância de Sensor (assumindo construtor: id, name, type, location, status)
            $sensor = new Sensor(
                $item['id'],
                $item['name'],
                $type,
                $item['location'] ?? '',
                $status
            );

            // Se tiver leitura anterior, atualiza
            if (isset($item['lastReading']) && method_exists($sensor, 'addReading')) {
                $sensor->addReading($item['lastReading']);
            }

            $this->sensors[$sensor->getId()] = $sensor;
        }
    }

    /**
     * Salva todos os sensores no arquivo JSON
     */
    private function saveSensors(): void
    {
        $out = [];
        foreach ($this->sensors as $sensor) {
            $entry = [
                'id'       => $sensor->getId(),
                'name'     => $sensor->getName(),
                'type'     => $sensor->getType(),
                'location' => $sensor->getLocation(),
                'status'   => $sensor->getStatus(),
                'metadata' => $sensor->getMetadata(),
            ];

            if (method_exists($sensor, 'getLatestReading')) {
                $reading = $sensor->getLatestReading();
                if ($reading !== null) {
                    $entry['lastReading'] = $reading;
                }
            } elseif (method_exists($sensor, 'hasReading') && $sensor->hasReading()) {
                $entry['lastReading'] = $sensor->getReading();
            }

            $out[] = $entry;
        }

        file_put_contents(
            $this->filePath,
            json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    /** @return Sensor[] */
    public function findAll(): array
    {
        return array_values($this->sensors);
    }

    public function findById(string $id): ?SensorInterface
    {
        return $this->sensors[$id] ?? null;
    }

    /**
     * @param Sensor|SensorInterface $sensor
     */
    public function save($sensor): void
    {
        $this->sensors[$sensor->getId()] = $sensor;
        $this->saveSensors();
    }

    public function delete(string $id): void
    {
        if (isset($this->sensors[$id])) {
            unset($this->sensors[$id]);
            $this->saveSensors();
        }
    }

    /**
     * @return Sensor[]
     */
    public function findByType(string $type): array
    {
        return array_values(array_filter(
            $this->sensors,
            fn(Sensor $s) => $s->getType() === $type
        ));
    }

    /**
     * @return Sensor[]
     */
    public function findByStatus(string $status): array
    {
        return array_values(array_filter(
            $this->sensors,
            fn(Sensor $s) => $s->getStatus() === $status
        ));
    }
}
