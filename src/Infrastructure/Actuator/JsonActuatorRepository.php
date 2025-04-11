<?php
// src/Infrastructure/Actuator/JsonActuatorRepository.php

namespace App\Infrastructure\Actuator;

use App\Core\Actuator\ActuatorRepositoryInterface;
use App\Domain\Actuator\Actuator;
use App\Domain\Actuator\ActuatorType;
use App\Domain\Actuator\ActuatorStatus;
use Exception;

class JsonActuatorRepository implements ActuatorRepositoryInterface
{
    private string $dataFile;

    public function __construct(?string $dataFile = null)
    {
        $this->dataFile = $dataFile ?? __DIR__ . '/../../data/actuator.json';

        // Criar arquivo se não existir
        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }

    public function findAll(): array
    {
        try {
            $actuatorsData = $this->readData();
            $actuators = [];

            foreach ($actuatorsData as $data) {
                $actuators[] = $this->hydrateActuator($data);
            }

            return $actuators;
        } catch (Exception $e) {
            return [];
        }
    }

    public function findById(string $id): ?Actuator
    {
        try {
            $actuatorsData = $this->readData();

            foreach ($actuatorsData as $data) {
                if ($data['id'] === $id) {
                    return $this->hydrateActuator($data);
                }
            }

            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function save(Actuator $actuator): bool
    {
        try {
            $actuatorsData = $this->readData();
            $updated = false;

            // Verifica se o atuador já existe
            foreach ($actuatorsData as $key => $data) {
                if ($data['id'] === $actuator->getId()) {
                    $actuatorsData[$key] = $this->dehydrateActuator($actuator);
                    $updated = true;
                    break;
                }
            }

            // Se não existe, adiciona
            if (!$updated) {
                $actuatorsData[] = $this->dehydrateActuator($actuator);
            }

            return $this->writeData($actuatorsData);
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete(string $id): bool
    {
        try {
            $actuatorsData = $this->readData();
            $found = false;

            foreach ($actuatorsData as $key => $data) {
                if ($data['id'] === $id) {
                    unset($actuatorsData[$key]);
                    $found = true;
                    break;
                }
            }

            if ($found) {
                // Reindexar o array
                $actuatorsData = array_values($actuatorsData);
                return $this->writeData($actuatorsData);
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function findByType(string $type): array
    {
        try {
            $actuatorsData = $this->readData();
            $actuators = [];

            foreach ($actuatorsData as $data) {
                if ($data['type'] === $type) {
                    $actuators[] = $this->hydrateActuator($data);
                }
            }

            return $actuators;
        } catch (Exception $e) {
            return [];
        }
    }

    public function findByLocation(string $location): array
    {
        try {
            $actuatorsData = $this->readData();
            $actuators = [];

            foreach ($actuatorsData as $data) {
                if ($data['location'] === $location) {
                    $actuators[] = $this->hydrateActuator($data);
                }
            }

            return $actuators;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lê o arquivo JSON
     */
    private function readData(): array
    {
        $jsonData = file_get_contents($this->dataFile);
        return json_decode($jsonData, true) ?? [];
    }

    /**
     * Escreve no arquivo JSON
     */
    private function writeData(array $data): bool
    {
        return (bool) file_put_contents(
            $this->dataFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * Converte um array em objeto Actuator
     */
    private function hydrateActuator(array $data): Actuator
    {
        $actuator = new Actuator(
            $data['id'],
            $data['name'],
            ActuatorType::from($data['type']),
            $data['location'],
            ActuatorStatus::from($data['status']),
            $data['metadata'] ?? []
        );

        // Se tiver última ação, adiciona ao atuador
        if (isset($data['lastAction'])) {
            $actuator->recordAction($data['lastAction']);
        }

        return $actuator;
    }

    /**
     * Converte um objeto Actuator em array
     */
    private function dehydrateActuator(Actuator $actuator): array
    {
        $data = [
            'id' => $actuator->getId(),
            'name' => $actuator->getName(),
            'type' => $actuator->getType()->value,
            'location' => $actuator->getLocation(),
            'status' => $actuator->getStatus()->value,
            'metadata' => $actuator->getMetadata()
        ];

        if ($actuator->hasLastAction()) {
            $data['lastAction'] = $actuator->getLastAction();
        }

        return $data;
    }
}