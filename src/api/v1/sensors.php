<?php

declare(strict_types=1);


use App\api\RequestParser;
use App\api\ResponseFactory;
use App\Infrastructure\Sensor\JsonSensorRepository;
use App\Infrastructure\Sensor\SensorSimulator;
use App\Service\SensorService;

// Bootstrap
$sensorRepository = new JsonSensorRepository();
$sensorSimulator = new SensorSimulator();
$sensorService = new SensorService($sensorRepository, $sensorSimulator);

// Roteamento
$method = RequestParser::getMethod();
$segments = RequestParser::getPathSegments();

// /api/sensors.php/[id]?[action]
$id = $segments[0] ?? null;
$action = $_GET['action'] ?? null;

try {
    // Rotas de coleção
    if (!$id) {
        if ($method === 'GET') {
            // Listar todos os sensores
            $sensors = $sensorService->getAllSensors();
            ResponseFactory::json(['data' => $sensors]);
        } elseif ($method === 'POST') {
            // Cria sensor
            $data = RequestParser::getJsonBody();

            // Validação básica
            if (empty($data['name']) || empty($data['type']) || empty($data['location'])) {
                ResponseFactory::error('Campos obrigatórios: name, type, location');
            }

            $sensor = $sensorService->createSensor($data);
            ResponseFactory::json(['data' => $sensor->getData()], 201);
        } else {
            ResponseFactory::error('Método não permitido', 405);
        }
    }
    // Rotas de recurso
    else {
        if ($method === 'GET') {
            if ($action === 'reading') {
                // Obter leitura do sensor
                $reading = $sensorService->getSensorReading($id);

                if ($reading === null) {
                    ResponseFactory::notFound('Sensor não encontrado');
                }

                ResponseFactory::json(['data' => $reading]);
            } else {
                // Obter detalhes do sensor
                $sensor = $sensorService->getSensorById($id);

                if (!$sensor) {
                    ResponseFactory::notFound('Sensor não encontrado');
                }

                ResponseFactory::json(['data' => $sensor->getData()]);
            }
        } elseif ($method === 'PUT' || $method === 'PATCH') {
            // Atualizar sensor
            $data = RequestParser::getJsonBody();
            $sensor = $sensorService->updateSensor($id, $data);

            if (!$sensor) {
                ResponseFactory::notFound('Sensor não encontrado');
            }

            ResponseFactory::json(['data' => $sensor->getData()]);
        } elseif ($method === 'POST' && $action === 'simulate') {
            // Simular leitura
            $data = RequestParser::getJsonBody();
            $reading = $sensorService->simulateSensorReading($id, $data);

            if ($reading === null) {
                ResponseFactory::notFound('Sensor não encontrado');
            }

            ResponseFactory::json(['data' => $reading]);
        } elseif ($method === 'DELETE') {
            // Excluir sensor
            $success = $sensorService->deleteSensor($id);

            if (!$success) {
                ResponseFactory::notFound('Sensor não encontrado');
            }

            ResponseFactory::json(['success' => true]);
        } else {
            ResponseFactory::error('Método não permitido', 405);
        }
    }
} catch (Exception $e) {
    ResponseFactory::error('Erro do servidor: ' . $e->getMessage(), 500);
}