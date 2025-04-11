<?php
// src/api/v1/actuators.php

use App\Domain\Actuator\ActuatorType;
use App\Domain\Actuator\ActuatorStatus;
use App\Infrastructure\Actuator\JsonActuatorRepository;
use App\Service\ActuatorService;
use App\api\RequestParser;
use App\api\ResponseFactory;

// Rotas para atuadores
$router->get('/src/api/v1/actuators', function() {
    header('Content-Type: application/json; charset=utf-8');

    // Instanciar repositório e serviço
    $actuatorRepository = new JsonActuatorRepository();
    $actuatorService = new ActuatorService($actuatorRepository);

    // Obter todos os atuadores
    $actuators = $actuatorService->getAllActuators();

    // Converter atuadores para arrays para garantir serialização correta
    extracted($actuators);
});

// Obter atuador por ID
$router->get('/src/api/v1/actuators/(\w+)', function($id) {
    header('Content-Type: application/json; charset=utf-8');

    // Instanciar repositório e serviço
    $actuatorRepository = new JsonActuatorRepository();
    $actuatorService = new ActuatorService($actuatorRepository);

    // Buscar atuador pelo ID
    $actuator = $actuatorService->getActuatorById($id);

    if (!$actuator) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Atuador não encontrado'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return;
    }

    // Converter para array para serialização
    $actuatorData = [
        'id' => $actuator->getId(),
        'name' => $actuator->getName(),
        'type' => $actuator->getType()->value,
        'location' => $actuator->getLocation(),
        'status' => $actuator->getStatus()->value,
        'metadata' => $actuator->getMetadata(),
        'lastAction' => $actuator->getLastAction()
    ];

    echo json_encode([
        'success' => true,
        'data' => $actuatorData
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// Criar novo atuador
$router->post('/src/api/v1/actuators', function() {
    header('Content-Type: application/json; charset=utf-8');

    // Parsear dados da requisição
    $requestData = RequestParser::parseJsonRequest();

    if (empty($requestData)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Dados inválidos'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return;
    }

    // Instanciar repositório e serviço
    $actuatorRepository = new JsonActuatorRepository();
    $actuatorService = new ActuatorService($actuatorRepository);

    // Criar atuador
    $actuator = $actuatorService->createActuator($requestData);

    if (!$actuator) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Não foi possível criar o atuador'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return;
    }

    // Retornar dados do atuador criado
    $actuatorData = [
        'id' => $actuator->getId(),
        'name' => $actuator->getName(),
        'type' => $actuator->getType()->value,
        'location' => $actuator->getLocation(),
        'status' => $actuator->getStatus()->value,
        'metadata' => $actuator->getMetadata()
    ];

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Atuador criado com sucesso',
        'data' => $actuatorData
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// Atualizar atuador
$router->put('/src/api/v1/actuators/(\w+)', function($id) {
    header('Content-Type: application/json; charset=utf-8');

    // Parsear dados da requisição
    $requestData = RequestParser::parseJsonRequest();

    // Instanciar repositório e serviço
    $actuatorRepository = new JsonActuatorRepository();
    $actuatorService = new ActuatorService($actuatorRepository);

    // Atualizar atuador
    $actuator = $actuatorService->updateActuator($id, $requestData);

    if (!$actuator) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Atuador não encontrado ou dados inválidos'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return;
    }

    // Retornar dados atualizados
    $actuatorData = [
        'id' => $actuator->getId(),
        'name' => $actuator->getName(),
        'type' => $actuator->getType()->value,
        'location' => $actuator->getLocation(),
        'status' => $actuator->getStatus()->value,
        'metadata' => $actuator->getMetadata()
    ];

    echo json_encode([
        'success' => true,
        'message' => 'Atuador atualizado com sucesso',
        'data' => $actuatorData
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// Remover atuador
$router->delete('/src/api/v1/actuators/(\w+)', function($id) {
    header('Content-Type: application/json; charset=utf-8');

    // Instanciar repositório e serviço
    $actuatorRepository = new JsonActuatorRepository();
    $actuatorService = new ActuatorService($actuatorRepository);

    // Remover atuador
    $success = $actuatorService->deleteActuator($id);

    if (!$success) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Atuador não encontrado ou erro ao remover'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Atuador removido com sucesso'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// Ligar atuador
$router->post('/src/api/v1/actuators/(\w+)/on', function($id) {
    header('Content-Type: application/json; charset=utf-8');

    // Instanciar repositório e serviço
    $actuatorRepository = new JsonActuatorRepository();
    $actuatorService = new ActuatorService($actuatorRepository);

    // Ligar atuador
    $actuator = $actuatorService->turnOnActuator($id);

    if (!$actuator) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Atuador não encontrado'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Atuador ligado com sucesso',
        'status' => $actuator->getStatus()->value
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// Desligar atuador
$router->post('/src/api/v1/actuators/(\w+)/off', function($id) {
    header('Content-Type: application/json; charset=utf-8');

    // Instanciar repositório e serviço
    $actuatorRepository = new JsonActuatorRepository();
    $actuatorService = new ActuatorService($actuatorRepository);

    // Desligar atuador
    $actuator = $actuatorService->turnOffActuator($id);

    if (!$actuator) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Atuador não encontrado'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Atuador desligado com sucesso',
        'status' => $actuator->getStatus()->value
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});