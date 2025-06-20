<?php
declare(strict_types=1);

use App\api\RequestParser;
use App\api\ResponseFactory;
use App\Infrastructure\Actuator\JsonActuatorRepository;
use App\Service\ActuatorService;

require_once __DIR__ . '/../../../vendor/autoload.php';

$repo    = new JsonActuatorRepository();
$service = new ActuatorService($repo);

$method   = RequestParser::getMethod();        // GET, POST, PUT, DELETE
$segments = RequestParser::getPathSegments();  // ex: [], ['123'], ['123','on']
$id       = $segments[0] ?? null;
$action   = $segments[1] ?? null;

try {
    // Rota: /actuators  (coleção)
    if ($id === null) {
        switch ($method) {
            case 'GET':
                // filtrar nulos antes de mapear para evitar erro de método chamado em null
                $actuators = array_filter($service->getAllActuators(), fn($a) => $a !== null);
                $all = array_map(fn($a) => $a->getData(), $actuators);
                ResponseFactory::json(['data' => $all]);
                break;

            case 'POST':
                $data = RequestParser::getJsonBody();
                if (empty($data['name']) || empty($data['location'])) {
                    ResponseFactory::error('Campos obrigatórios: name, location');
                }
                $created = $service->createActuator($data);
                if (!$created) {
                    ResponseFactory::error('Falha ao criar atuador');
                }
                ResponseFactory::json(['data' => $created->getData()], 201);
                break;

            default:
                ResponseFactory::error('Método não permitido', 405);
        }
        exit;
    }

    // Rota: /actuators/{id} ou /actuators/{id}/{action}
    switch ($method) {
        case 'GET':
            $actuator = $service->getActuatorById($id);
            if (!$actuator) {
                ResponseFactory::notFound('Atuador não encontrado');
            }
            ResponseFactory::json(['data' => $actuator->getData()]);
            break;

        case 'PUT':
        case 'PATCH':
            $data = RequestParser::getJsonBody();
            $updated = $service->updateActuator($id, $data);
            if (!$updated) {
                ResponseFactory::notFound('Falha ao atualizar atuador');
            }
            ResponseFactory::json(['data' => $updated->getData()]);
            break;

        case 'DELETE':
            $ok = $service->deleteActuator($id);
            if ($ok) {
                ResponseFactory::json(['success' => true]);
            } else {
                ResponseFactory::notFound('Falha ao remover atuador');
            }
            break;

        case 'POST':
            // Se existe uma ação após o ID, trata ligar/desligar
            if ($action === 'on' || $action === 'off') {
                $actuator = $action === 'on'
                    ? $service->turnOnActuator($id)
                    : $service->turnOffActuator($id);

                if (!$actuator) {
                    ResponseFactory::notFound('Atuador não encontrado');
                }
                ResponseFactory::json([
                    'data' => [
                        'id'     => $actuator->getId(),
                        'status' => $actuator->getStatus()->value,
                    ]
                ]);
            } else {
                ResponseFactory::error('Ação desconhecida');
            }
            break;

        default:
            ResponseFactory::error('Método não permitido', 405);
    }
} catch (Throwable $e) {
    ResponseFactory::error('Erro interno: ' . $e->getMessage(), 500);
}
