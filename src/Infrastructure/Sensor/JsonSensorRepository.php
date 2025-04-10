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
            // Cria o diretório, se necessário
            $directory = dirname($this->filePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Cria um arquivo vazio se não existir
            file_put_contents($this->filePath, json_encode([]));
            return;
        }

        $jsonContent = file_get_contents($this->filePath);
        if (empty($jsonContent)) {
            return;
        }

        $sensorsData = json_decode($jsonContent, true);
        if (!is_array($sensorsData)) {
            return;
        }

        foreach ($sensorsData as $sensorData) {
            // Verifica a estrutura correta da classe Sensor
            $type = SensorType::tryFrom($sensorData['type']) ?? SensorType::UNKNOWN;
            $status = SensorStatus::tryFrom($sensorData['status'] ?? 'active') ?? SensorStatus::ACTIVE;

            // Adapte esta chamada consoante a assinatura correta do construtor da classe Sensor
            $sensor = new Sensor(
                $sensorData['id'],
                $sensorData['name'],
                $type,
                $sensorData['location'] ?? '',
                $status,
            );

            // Adicionar leitura se houver (verifique o método correto)
            if (isset($sensorData['lastReading'])) {
                // Use o método correto para atualizar leituras
                if (method_exists($sensor, 'updateReading')) {
                    $sensor->updateReading($sensorData['lastReading']);
                } elseif (method_exists($sensor, 'addReading')) {
                    $sensor->addReading($sensorData['lastReading']);
                }
            }

            $this->sensors[$sensor->getId()] = $sensor;
        }
    }

    /**
     * Salva todos os sensores no arquivo JSON
     */
    private function saveSensors(): void
    {
        $sensorsData = [];

        foreach ($this->sensors as $sensor) {
            $sensorData = [
                'id' => $sensor->getId(),
                'name' => $sensor->getName(),
                'type' => $sensor->getType()->value,
                'location' => $sensor->getLocation(),
                'status' => $sensor->getStatus()->value,
                'metadata' => $sensor->getMetadata()
            ];

            // Incluir leitura no JSON se existir (verifique o método correto)
            if (method_exists($sensor, 'hasReading') && $sensor->hasReading()) {
                $sensorData['lastReading'] = $sensor->getReading();
            } elseif (method_exists($sensor, 'getLatestReading')) {
                $reading = $sensor->getLatestReading();
                if ($reading !== null) {
                    $sensorData['lastReading'] = $reading;
                }
            }

            $sensorsData[] = $sensorData;
        }

        file_put_contents(
            $this->filePath,
            json_encode($sensorsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * Retorna todos os sensores
     */
    public function findAll(): array
    {
        return array_values($this->sensors);
    }

    /**
     * Busca um sensor pelo ID
     */
    public function findById(string $id): ?SensorInterface
    {
        return $this->sensors[$id] ?? null;
    }

    /**
     * Salva um sensor
     */
    public function save(Sensor|SensorInterface $sensor): void
    {
        $this->sensors[$sensor->getId()] = $sensor;
        $this->saveSensors();
    }

    /**
     * Exclui um sensor pelo ID
     */
    public function delete(string $id): void
    {
        if (isset($this->sensors[$id])) {
            unset($this->sensors[$id]);
            $this->saveSensors();
        }
    }

    /**
     * Busca sensores por tipo
     */
    public function findByType(SensorType $type): array
    {
        return array_values(array_filter(
            $this->sensors,
            fn($sensor) => $sensor->getType() === $type
        ));
    }

    /**
     * Busca sensores por status
     */
    public function findByStatus(SensorStatus $status): array
    {
        return array_values(array_filter(
            $this->sensors,
            fn($sensor) => $sensor->getStatus() === $status
        ));
    }
}