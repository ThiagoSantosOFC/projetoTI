<?php
// src/api/v1/sensors.php
declare(strict_types=1);

use App\api\RequestParser;
use App\api\ResponseFactory;
use App\Infrastructure\Sensor\JsonSensorRepository;
use App\Infrastructure\Sensor\SensorSimulator;
use App\Service\SensorService;

require_once __DIR__ . '/../../../vendor/autoload.php';

// 1) Bootstrap
$repo      = new JsonSensorRepository();
$simulator = new SensorSimulator();
$service   = new SensorService($repo, $simulator);

// 2) Parser
$method   = RequestParser::getMethod();        // GET, POST, PUT, DELETE
$segments = RequestParser::getPathSegments();  // ex: [], ['123'], ['123']
$id       = $segments[0] ?? null;
$action   = RequestParser::getQueryParams('action'); // ou $_GET['action']

try {
    // 3) Sem ID → coleção
    if ($id === null) {
        switch ($method) {
            case 'GET':
                $data = array_map(fn($s) => $s->getData(), $service->getAllSensors());
                ResponseFactory::json(['data' => $data]);
                break;

            case 'POST':
                $payload = RequestParser::getJsonBody();
                if (empty($payload['name'] || $payload['type'] || $payload['location'])) {
                    ResponseFactory::error('Campos obrigatórios: name, type, location');
                }
                $sensor = $service->createSensor($payload);
                ResponseFactory::json(['data' => $sensor->getData()], 201);
                break;

            default:
                ResponseFactory::error('Método não permitido', 405);
        }
        exit;
    }

    // 4) Com ID → recurso ou ação especial
    switch ($method) {
        case 'GET':
            if ($action === 'reading') {
                $reading = $service->getSensorReading($id);
                $reading === null
                    ? ResponseFactory::notFound('Sensor não encontrado')
                    : ResponseFactory::json(['data' => $reading]);
            } else {
                $sensor = $service->getSensorById($id);
                $sensor === null
                    ? ResponseFactory::notFound('Sensor não encontrado')
                    : ResponseFactory::json(['data' => $sensor->getData()]);
            }
            break;

        case 'PUT':
        case 'PATCH':
            $payload = RequestParser::getJsonBody();
            $updated = $service->updateSensor($id, $payload);
            $updated === null
                ? ResponseFactory::notFound('Sensor não encontrado')
                : ResponseFactory::json(['data' => $updated->getData()]);
            break;

        case 'POST':
            if ($action === 'simulate') {
                $payload = RequestParser::getJsonBody();
                $reading = $service->simulateSensorReading($id, $payload);
                $reading === null
                    ? ResponseFactory::notFound('Sensor não encontrado')
                    : ResponseFactory::json(['data' => $reading]);
            } else {
                ResponseFactory::error('Ação desconhecida');
            }
            break;

        case 'DELETE':
            $deleted = $service->deleteSensor($id);
            $deleted
                ? ResponseFactory::json(['success' => true])
                : ResponseFactory::notFound('Sensor não encontrado');
            break;

        default:
            ResponseFactory::error('Método não permitido', 405);
    }
} catch (Throwable $e) {
    ResponseFactory::error('Erro interno: '.$e->getMessage(), 500);
}
